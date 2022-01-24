# Changelog

## 1.2.3

- Added option in volumes to prevent from showing up in upcoming releases

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
