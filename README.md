![Banner](https://raw.githubusercontent.com/s22-tech/MatchMaker/master/screenshots/banner.png)

A simple and secure PHP script to help people find their perfect mate.

Developed and maintained by [Marc Coleman](https://github.com/s22-tech). Copyright s22Tech.

Demo located at [mm.s22.us](https://mm.s22.us/)

## Overview

This script started life as Robin's Nest from the "Learning PHP, MySQL, & JavaScript" book by https://github.com/RobinNixon but soon took on a life of it's own and now has become production worthy.  I plan to take this a lot further with the help of the GitHub community, however the underlying philosophy of secure, self-contained, and lightweight will always remain.

The look and feel can be changed in a flash simply by replacing the bootstrap file with your choice from https://bootswatch.com
Check out their different themes.

Click the "Watch" button to be notified of all updates.

## Requirements

- PHP 8.0+

## Features

- PHP 8 compatibility
- beautiful new UI using Bootstrap 5
- jQuery 3.6.0
- responsive template suitable for both desktop and mobile
- added .inc.php suffix where beneficial
- fanatical focus on security
- local fonts - no calls to Google or any other 3rd party
- choose either a private (invitation only) or public (open to all) site
- ability to block another member from seeing your profile
	- the blockee will not be able to see the blocker and vice versa
	- ability to hide individual messages
- added marital status, gender to members table (more to come)
- ability to filter members based on gender
- shows only private messages after clicking on a member
- upon login, the user is automatically taken to their page
- logout now returns the user to index.php
- clicking a gender filter will automatically choose that filter - no "Submit" button
- placeholder image for users that haven't uploaded a photo
- switched to a grid view on members and friends pages
- members will be shown the time of each message in their own local timezone
- distinguish between private and public messages better
- replaced "← you are following" with a solid star (unfilled for not following)
- switched to PDO and uses placeholders where appropriate
- delete old passwords when a new one is created
- script creates a .htpasswd entry upon signup (optional)
- maximum screen name length is 16 chars
- salt passwords and store them as one-way hash strings
- scale images in PHP
- show public messages for 1 month only (settings)
- save the original uploaded photos and append a timestamp so they don't get overwritten
- send an email to a member when they get a message
- ability for members to change password
- added a simple contact form
- placed the refresh button above the messages so it's always visible
- log users visits (optional)
- images are super secure when stored above the root directory

## Installation
- Create a new database on your server
- Run the  installer at `www.your-domain/mm/install/` and fill in the fields
- You're done!

## To Do

- add ability to upload multiple photos
- add an admin section
- add a forgotten password feature
- create an add-on/modules feature to keep the core small

## License

Licensed under the [MIT license](http://opensource.org/licenses/MIT).

## Screenshot

![Profile Page](https://raw.githubusercontent.com/s22-tech/MatchMaker/master/screenshots/profile-page.png)


