<div class="modal" id="livestream_scanner">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ __('Barcode Scanner') }}</h4>
            </div>
            <div class="modal-body" style="position: static">
                <div class="row">
                    <div class="col-md-12">
                        <label for="input-stream_constraints" class="col-form-label">{{ __('Camera') }}</label>
                        <select id="deviceSelection" name="input-stream_constraints" class="form-select">
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div id="interactive" class="viewport"></div>
                    <div class="error"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>

<style>
    #interactive.viewport {
        position: relative;
        width: 100%;
        height: auto;
        overflow: hidden;
        text-align: center;
    }

    #interactive.viewport>canvas,
    #interactive.viewport>video {
        max-width: 100%;
        width: 100%;
    }

    canvas.drawing,
    canvas.drawingBuffer {
        position: absolute;
        left: 0;
        top: 0;
    }
</style>

<script type="text/javascript">
    document.addEventListener('livewire:load', function() {
        // Create the QuaggaJS config object for the live stream
        var liveStreamConfig = {
            frequency: 10,
            inputStream: {
                type: "LiveStream",
                constraints: {
                    width: {
                        min: 640
                    },
                    height: {
                        min: 480
                    },
                    facingMode: "environment",
                },
            },
            locator: {
                patchSize: "medium",
                halfSample: true,
            },
            numOfWorkers: (navigator.hardwareConcurrency ? navigator.hardwareConcurrency : 4),
            decoder: {
                readers: [
                    "ean_reader"
                ],
            },
            locate: true
        };
        // The fallback to the file API requires a different inputStream option.
        // The rest is the same
        var fileConfig = $.extend({},
            liveStreamConfig, {
                inputStream: {
                    size: 800
                }
            }
        );
        // Start the live stream scanner when the modal opens
        $('#livestream_scanner').on('shown.bs.modal', function(e) {
            var streamLabel = Quagga.CameraAccess.getActiveStreamLabel();
            Quagga.CameraAccess.enumerateVideoDevices()
                .then(function(devices) {
                    function pruneText(text) {
                        return text.length > 30 ? text.substr(0, 30) : text;
                    }
                    debugger;
                    var $deviceSelection = document.getElementById("deviceSelection");
                    while ($deviceSelection.firstChild) {
                        $deviceSelection.removeChild($deviceSelection.firstChild);
                    }
                    devices.forEach(function(device) {
                        var $option = document.createElement("option");
                        $option.value = device.deviceId || device.id;
                        $option.appendChild(document.createTextNode(pruneText(device.label || device.deviceId || device.id)));
                        $option.selected = streamLabel === device.label;
                        $deviceSelection.appendChild($option);
                    });
                    initCamera();
                });
        });

        $('#deviceSelection').change(function() {
            var deviceId = $(this).val();
            liveStreamConfig.inputStream.constraints.deviceId = deviceId;
            Quagga.stop();
            initCamera();
        });

        function initCamera() {
            Quagga.init(
                liveStreamConfig,
                function(err) {
                    if (err) {
                        $('#livestream_scanner .modal-body .error').html('<div class="alert alert-danger"><strong><span class="fa fa-exclamation-triangle"></span> ' + err.name + '</strong>: ' + err.message + '</div>');
                        Quagga.stop();
                        return;
                    } else {
                        $('#livestream_scanner .modal-body .error').html('');
                    }
                    Quagga.start();
                }
            );
        }

        // Make sure, QuaggaJS draws frames an lines around possible
        // barcodes on the live stream
        Quagga.onProcessed(function(result) {
            var drawingCtx = Quagga.canvas.ctx.overlay,
                drawingCanvas = Quagga.canvas.dom.overlay;

            if (result) {
                if (result.boxes) {
                    drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute("width")), parseInt(drawingCanvas.getAttribute("height")));
                    result.boxes.filter(function(box) {
                        return box !== result.box;
                    }).forEach(function(box) {
                        Quagga.ImageDebug.drawPath(box, {
                            x: 0,
                            y: 1
                        }, drawingCtx, {
                            color: "green",
                            lineWidth: 2
                        });
                    });
                }

                if (result.box) {
                    Quagga.ImageDebug.drawPath(result.box, {
                        x: 0,
                        y: 1
                    }, drawingCtx, {
                        color: "#00F",
                        lineWidth: 2
                    });
                }

                if (result.codeResult && result.codeResult.code) {
                    Quagga.ImageDebug.drawPath(result.line, {
                        x: 'x',
                        y: 'y'
                    }, drawingCtx, {
                        color: 'red',
                        lineWidth: 3
                    });
                }
            }
        });

        // Once a barcode had been read successfully, stop quagga and
        // close the modal after a second to let the user notice where
        // the barcode had actually been found.
        Quagga.onDetected(function(result) {
            if (result.codeResult.code) {
                @this.set('isbn', result.codeResult.code);
                Quagga.stop();
                setTimeout(function() {
                    $('#livestream_scanner').modal('hide');
                }, 1000);
            }
        });

        // Stop quagga in any case, when the modal is closed
        $('#livestream_scanner').on('hide.bs.modal', function() {
            if (Quagga) {
                Quagga.stop();
            }
        });

        // Call Quagga.decodeSingle() for every file selected in the
        // file input
        $("#livestream_scanner input:file").on("change", function(e) {
            if (e.target.files && e.target.files.length) {
                Quagga.decodeSingle($.extend({}, fileConfig, {
                    src: URL.createObjectURL(e.target.files[0])
                }), function(result) {
                    alert(result.codeResult.code);
                });
            }
        });
    });
</script>
