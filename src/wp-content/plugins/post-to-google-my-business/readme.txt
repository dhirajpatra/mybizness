=== Post to Google My Business ===
Contributors: koen12344, tycoon12344, freemius
Donate link: https://tycoonmedia.net/?utm_source=repository&utm_medium=link&utm_campaign=donate
Tags: google my business, google, business, posts, post, local search, google my business posts, google places, google plus, google+
Requires at least: 4.9.0
Tested up to: 5.3.2
Stable tag: 2.2.9
Requires PHP: 5.6.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Easily create new posts on Google My Business directly from the WordPress Dashboard!

== Description ==

The new posts functionality in Google My Business is a great way to improve the presence of your, or your clients' business on Google. It can be a hassle however to have to log in to Google My Business every time you want to create a new post, likewise it can be an easy thing to forget.

Don't miss out on the SEO benefit, and save time by creating posts on Google My Business directly from the WordPress Dashboard!

Use the Auto-post feature to instantly publish your latest WordPress post to Google My Business, based on a preset template and the posts' featured image.

The Post to Google My Business plugin utilizes the official Google My Business API with secure oAuth authentication to ensure your Google account is safe.


= Features =
* Create, edit or delete posts without having to visit your Google My Business page
* Quickly publish your latest WordPress posts to GMB using the Auto-post feature
* Network- and site-level Multisite support
* Support publishing to GMB from external apps (such as Windows Live Writer, Zapier, Integromat, ManageWP, InfiniteWP, MainWP etc)
* Uses official Google My Business API
* Developer friendly. Uses the latest built-in WordPress functions and has various actions/filters to hook into.
* Translatable. Uses built-in WordPress functions for easy translation.
* Gutenberg compatible

> **Time-saving features available in the Premium version:**
>
> * Schedule Google My Business posts for automatic publishing in the future
> * Create video posts
> * Create event and offer posts
> * Create new Google My Business posts from any WordPress post type (e.g. WooCommerce products)
> * Pick location per post, or post to multiple locations at once
> * Automatic re-posting - Automatically repost your GMB posts at preset intervals and x amount of times
> * Make unique posts using Spintax and %variables%
> * Post Campaigns - Create posts on GMB that aren't tied to any specific WordPress post or page.
> * Post Analytics - See how many views and clicks your post has gotten straight from the WordPress Dash
> * Increased posting (API) limits
> * Priority email support from the developer of the plugin
> * Support and updates for 1 year
> * Much more!
>
> **[Learn more about Post to Google My Business Premium](https://tycoonmedia.net/?utm_source=repository&utm_medium=link&utm_campaign=learn_more&utm_content=description)**

= Great support! =
We're here to help in case you're having trouble using Post to Google My Business. Just ask in the support forum and we'll get back to you ASAP. Feedback and ideas to improve the plugin are always welcome.

== Installation ==

Installing and configuring Post to Google My Business is easy!

1. Upload the plugin files to the `/wp-content/plugins/post-to-google-my-business` directory, or install it through the plugins page within the WordPress Dashboard.
2. Activate the plugin through the **Plugins** page in WordPress
3. Go to the **Post to GMB** > **Settings** page to configure the plugin
4. To allow your website to post to Google My Business on your behalf, click **Connect to Google My Business**. Confirm the authorization using the Google account that holds your business location.
5. You will be redirected back to the settings page. Select your business location and press **Save Changes**.
6. All set! When creating a new WordPress **Post** there will a new metabox that allows you to create posts on Google My Business.


== Frequently Asked Questions ==

= Can I use this plugin on a localhost installation? =

Yes, but you may run into errors if you add a link or image to your post. Google will try to fetch your image/video, or resolve the link to your website, but if your localhost installation can't be reached from the outside world, it won't be able to do so.
The quick post feature will not work at all in that case, because it uses the URL and Featured Image of your post.

= Why is/are my location(s) grayed out? =

Not every Google My Business listing is allowed to use the "LocalPostAPI". Especially chains of locations (businesses with 10+ locations) are blocked from using it. This means the plugin can't post to them.

== Screenshots ==

1. Customizing and posting GMB post
2. Using the Auto-post feature
3. Creating a "What's new" post
4. Creating an event
5. Creating an offer post
6. Auto-post template settings

== Changelog ==

= 2.2.9 =
* Fix auto-post incorrectly throwing 1500 character error
* Hide "Save draft" button on already published post

> **Premium**
>
> * Fix caption on "Save Template" button switching to "Publish" when adding schedule

= 2.2.8 =
* Fix gutenberg issue caused by 2.2.7

= 2.2.7 =
* Fix compatibility issue with Yoast SEO & Classic Editor

= 2.2.6 =
* Updated .pot file and Grunt scripts
* Updated Dutch, Russian and Ukrainian translations
* Display post publish date in metabox

= 2.2.5 =
* Fix duplicate post issue

> **Premium**
>
> * Remove Auto-post checkbox being shown on campaigns page

= 2.2.4 =
* Fix issues with CTA URL on button
* Improve updater (again)
* Fix default value for CTA URL field
* Fix CTA url field disappearing when loading post

> **Premium**
>
> * Fix repost schedule being improperly parsed

= 2.2.3 =
* Improve updater

= 2.2.2 =
* Fix Form field parser allowing dates in the past

= 2.2.1 =
* Fix for Gutenberg autopost
* Restore filter functions
* Update Freemius SDK

= 2.2 =
* Moved API communication to an asynchronous process
* Added Auto-post template editor
* Added Debug info tab to settings page
* Added dialog with created posts
* Made UI more intuitive
* Added some fixes to improve compatibility with Gutenberg
* Added functionality to fetch image from content or use the featured image
* Tons of improvements and bug fixes "under the hood"
* Lots more to come in future updates!

> **Premium**
>
> * Made re-posting much more flexible
> * Re-posted posts will now appear as a separate post in the metabox

= 2.1.18 =
> **Premium**
>
> * Actually parse the relative datetimes on scheduled posts :)
> * Disable Product post support (removed from GMB api)
> * Improve display of datetimes, better timezone handling

= 2.1.17 =
> **Premium**
>
> * Allow relative time notation in datetimepickers

= 2.1.16 =
* Improved parsing of post content

= 2.1.11 =
* Improve development & deployment methods

= 2.1.10 =
* Update Freemius SDK
* Remove shortcodes from auto-post by default

= 2.1.9 =
* Security fix

= 2.1.8 =
* Fixes issue with image URL spinner with no image set (PHOTO media error)

= 2.1.7 =
* Properly delete child posts and schedules when deleting parent post

= 2.1.6 =
> **Premium**
>
> * Spin image URL

= 2.1.5 =
* Fix issue when trying to load more than 100 locations
* Apply filter mbp_get_locations filter to cached locations

= 2.1.4 =
* Fix settings page conflict caused by plugins using old version of WeDevs Settings API
* Fix Learn more link about grayed out locations

= 2.1.3 =
* Fix auto-post sending campaign posts
* Improve Gutenberg compatibility

= 2.1.2 =
* Fix auto-post being triggered too early
* Simplify business selector

> **Premium**
>
> * Fix repost sheduled events not getting deleted
> * Fix reposts not being published when recurrence was set to 0
> * Fix reposts being posted twice when not scheduled

= 2.1.1 =
* Increase API timeout
* Fix multiline posts
* Improve error messages
* Fix API token refresh requests when network activated

= 2.1.0 =
* Improves location loading (+100 Locations in account)
* Check whether locations have access to the Posts API
* Call Now button support
* Improves auto-post logic
* Restructuring code

> **Premium**
>
> * Improves posting to multiple locations at once
> * Product post support

= 2.0.10 =
* Update Freemius SDK
* Add filters to Auto post feature

> **Premium**
>
> * Add option to edit Auto post URL

= 2.0.9 =
* Fix 500 error on PHP 5.4 https://wordpress.org/support/topic/500-error-when-adding-a-new-post/

= 2.0.8 =
* Fix issue some settings getting deleted when updating from 2.0.6 to 2.0.7
* Improve compatibility with external publishing apps and services
* Made plugin settings page more intuitive

= 2.0.7 =
* Strip HTML from posts
* Cut posts to 1500 characters
* Added word and character counters
* Simplified business selector
* Removed user selector, now integrated with business selector
* Better support for grouped locations
* Allow filtering/searching of locations
* Remove references to datetimepicker

> **Premium**
>
> * Added buttons to Select/Deselect all locations at once
> * Fixed some issues with Pro features in trial

= 2.0.6 =
* Add ability to save posts as draft
* Add option to invert the "Quick Publish" checkbox. Allows you to automatically publish to GMB when the WordPress post is created externally
* Fix display issue when location has no thumbnail
* Add placeholder index.php files to plugin folders
* Show "No GMB posts found." again when last post is deleted.
* Close form when the post currently being edited is being deleted

> **Premium**
>
> * Fix Premium features not being enabled when in trial
> * Fix scheduled posts not being posted in Pro
> * Fix issue with post type settings causing error when no post type is selected and settings are saved
> * Check default location when creating new post
> * Fix Metabox not visible on post campaigns
> * Fix invisible month switching icons on the datetimepicker


= 2.0.5 =
* Fix location info when importing old posts
* Fix Google link not appearing when using quickpost

= 2.0.4 =
* Version function magically disappeared, fixed

= 2.0.3 =
* Fixes updating issue on multisite

= 2.0.2 =
* Fix issue causing fatal error on PHP 5.6 < https://wordpress.org/support/topic/2-0-1-update-crashes-site/

= 2.0.1 =
* Fixes issue with certain Google post fields not updating when updating post

= 2.0.0 =
* Improved metabox, easily create multiple GMB posts per WordPress post
* Supports new Google post types
* Added Quick post feature to create GMB posts based on a preset template. All you have to do is tick the checkbox to post!
* Fixed plugin conflict causing endless loop
* Improved and simplified settings page
* Improved business selector
* And much, much more!

> **New premium features**
>
> * Automatic reposting - Choose time interval and amount of times to repost
> * Post spinning and variables - Make your posts unique using Spintax and variables such as %site_name%, %post_title%
> * Video posts
>
> **[Learn more about Post to Google My Business Premium](https://tycoonmedia.net/?utm_source=repository&utm_medium=link&utm_campaign=learn_more&utm_content=changelog)**

= 1.2 =
* Added logic for cleaning up options when uninstalling plugin
* Improved admin/error notices

= 1.1.1 =
* Fixed PHP compatibility issue https://wordpress.org/support/topic/getting-parse-error-when-installing/

= 1.1 =
* Improved business location selector
* Fixed timepicker issues
* Javascript for metabox now in separate file
* Fixed incorrect language code causing issues when posting
* Various other small improvements and fixes

= 1.0 =
* Initial release

== Upgrade Notice ==

= 2.2.9 =
* Fixes auto-post incorrectly throwing 1500 character error

= 2.2.7 =
* Fixes compatibility issue with Yoast SEO & Classic Editor

= 2.2.6 =
* Changes how scheduled posts and re-posts are handled. Please check and confirm your scheduled posts after upgrading.

= 2.1.10 =
* Fixes error in multisite admin

= 2.1.9 =
* Security fix

= 2.1.8 =
* Fixes PHOTO media format error

= 2.1.7 =
* Cleans up orphaned schedules of deleted WP posts

= 2.1.6 =
* Spin image URL

= 2.1.5 =
* Fixes loading 100+ locations

= 2.1.4 =
* Fixes settings page location selector conflict

= 2.1.3 =
* Fix auto-post sending campaign posts

= 2.1.2 =
* Fixes various issues with automatic repost

= 2.1.1 =
* Various small fixes

= 2.1.0 =
* Product and Call Now button support

= 2.0.10 =
* Improved Multisite support

= 2.0.9 =
* Fix 500 error on PHP 5.4

= 2.0.8 =
* Better compatibility with external publishing apps and services

= 2.0.7 =
* Strip HTML from posts
* Cut posts to 1500 characters
* Added word and character counters
* Simplified business selector
* Allow filtering/searching of locations

= 2.0.6 =
* Adds ability to save post drafts, various other small fixes

= 2.0.5 =
* Fixes issues importing posts from older versions

= 2.0.4 =
* Fixes metabox not working

= 2.0.3 =
* Fixes update issue

= 2.0.2 =
* Fixes fatal error on older php versions

= 2.0.1 =
* Fixes issue with certain Google post fields not updating when updating post

= 2.0.0 =
* Major update

= 1.2 =
* Improves admin notices, cleanup options when uninstalling

= 1.1.1 =
* Fixes PHP compatibility issue

= 1.1 =
* Improves business selector, fixes various small issues

= 1.0 =
* Initial release
