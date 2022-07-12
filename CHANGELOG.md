# Changelog

## 2.3.0 (2022-07-12)

- Added cover images for volumes
- Added subscription indicator in upcoming releases
- Added source titles which can be fetched via Manga Passion API
- Added Book Depository to series lookup options
- Added bulk update for volumes (allows setting status and/or price)
- Added new search options for series: Book Depository, Manga Passion, AniList and MyAnimeList.net
- Added option to create volumes when creating a series not via Manga Passion API
- Added page size to categories
- Added new statistics
- Added option to set the image type for covers (jpg, png, gif, bmp, webp)
- Moved refresh button (Manga Passion API) to the top next to the edit button
- Improved rendering performance through lazy loading and async decoding images
- Release dates of articles are now formatted
- Cover images for volumes are now fetched via Manga Passion API
- Fixed category deletion when genres are assigned to series
- Fixed fetching volumes via Manga Passion API when a series has more than 100 volumes
- Fixed various other bugs
- Ignore volumes without number via Manga Passion API as they were overwriting volume 1

## 2.2.0 (2022-05-14)

- Updating a series via Manga Passion API now updates the price for volumes with status "New"
- Added demographics and genres which are fetched via Manga Passion API
- Added series descriptions which are fetched via Manga Passion API
- Added cron job and email notification for updating metadata via Manga Passion API
- Added Select2 to replace native dropdowns
- Added context information to website title
- Series can now be paused or canceled
- Changed series' completion status to be more dynamic
- Navbar is now sticky for easier access on mobiles devices
- Image-Sources are now stored in the database and updated via Manga Passion API
- Updated to latest Laravel version, now requiring PHP 8.0.2 or greater
- Fixed semantic layout errors
- Updated translations

## 2.1.0 (2022-02-07)

- Added date format to user profile
- Improve updating series and volumes data via Manga Passion API
- ISBN and Publish Date of a volume are now optional
- Reordering volumes makes sure that no gaps are left
- When setting a series as subscribed, all volumes with status "New" will be set to "Ordered"

## 2.0.3 (2022-02-05)

- Applied fixes from 2.0.2 to update button

## 2.0.2 (2022-02-05)

- Fix volume creation when number is not set
- Use number of volumes for completed series, use source for ongoing series

## 2.0.1 (2022-02-05)

- Added missing translation for "Update" button in a series

## 2.0.0 (2022-02-05)

### Breaking Changes

- Integrated [Manga Passion API](https://www.manga-passion.de)
  -  Fetch series metadata
  -  Fetch and create publishers
  -  Automatically create volumes when creating a series

This project's target audience is now manga readers from German speaking countries.

### New Features

- Added "Subscription active" to series to always create ordered volumes

## 1.5.4 (2022-02-02)

- Removed aspect ratio from experimental barcode scanner to hopefully fix iOS camera issues

## 1.5.3 (2022-02-02)

- Fixed camera selection being empty when reopening scanner

## 1.5.2 (2022-02-02)

- Add camera selection to experimental barcode scanner

## 1.5.1 (2022-02-02)

- Improved ISBN validation
- Fixed possible errors when publish date is being empty

## 1.5.0 (2022-02-01)

- Added experimental barcode scanner when creating volumes

## 1.4.2 (2022-02-01)

- Version notifications are now persistent
- Notifications are now shown in the bottom right

## 1.4.1 (2022-02-01)

- Fixed login redirect

## 1.4.0 (2022-02-01)

- Added additional statistics
- Added favicon
- Added Web App Manifest to allow installation on mobile devices
- Fonts are no longer loaded from Google
- Fixed "No data" not spanning over all columns for volumes table inside a series

## 1.3.0 (2022-01-28)

- Added publishers in the Administration menu
- Added publishers to series
- Added series/articles count next to category name
- Added a pie chart for volume statistics in series
- Filtering also applies to statistics now
- Fixed filter being lost when updating an upcoming release
- Renamed global statistics to overview

## 1.2.5 (2022-01-25)

- Made table header for upcoming releases sticky

## 1.2.4 (2022-01-25)

- Increased height of upcoming releases and statistics
- Hide search bar when not being authenticated

## 1.2.3 (2022-01-25)

- Added option in volumes to prevent from showing up in upcoming releases
- Fixed prices when creating a volume and not having set a default price in the series

## 1.2.2 (2022-01-22)

- A local version of flasher is now used instead of loading it via a CDN

## 1.2.1 (2022-01-22)

- Added confirmation dialog when deleting categories
- Added confirmation dialog when deleting series
- Added confirmation dialog when deleting articles
- Added confirmation dialog when deleting volumes
- Fixed deletion of article categories
- Fixed deletion of articles

## 1.2.0 (2022-01-22)

- Added articles
- Added type to categories (books or articles)
- Added user profile
- Added changing password in user profile
- Added option to toggle ISBN formatting in user profile
- Added search bar to filter galleries and upcoming releases
- Moved NSFW toggle to user dropdown
- Changed layout of forms
- Fixed completion status for cards (read volumes were not taken into account)

## 1.1.1 (2022-01-15)

- Fixed version update notification not being shown
- Show version update notification every 30 minutes instead of every 60 minutes

## 1.1.0 (2022-01-15)

- Added "Read" as status after "Delivered"

## 1.0.5 (2022-01-11)

- Added notification when a new version is available
- Added German language
- Changed default app title
- Hide Administration from navigation when not being logged in

## 1.0.4 (2022-01-08)

- Fixed exception when creating multiple volumes in a row due to missing price

## 1.0.3 (2022-01-08)

- Fixed creation of new volumes when no default price was set in the series

## 1.0.2 (2022-01-03)

- Removed replacing of commas when entering the default price while creating a series
- Fixed volumes not saving the default price inherited from the series

## 1.0.1 (2022-01-03)

- Increased min width for volume title and ISBN in upcoming releases
- Increased min width for ISBN in volumes table

## 1.0.0 (2022-01-03)

- Added categories
- Added series to categories
- Added volumes to series
- Added list of upcoming releases (new, ordered, shipped)
- Added Google Books API to fetch publish dates of volumes
- Added NSFW flag for series
- Added reordering of volumes
- Added global statistics
- Added total worth of collection (delivered volumes only)
