# Last.FM Bludit Sidebar Plugin
A plugin for Bludit that allows you to list your most scrobbled tracks on Last.FM within a given date in your sidebar.

I wanted to be able to dynamically list what music I listen to on my website, and given that there was no existing plugin to already do this, I decided to create one.

This plugin is for use on the CMS/blog engine [Bludit](http://bludit.com).

There are currently 4 options to change how the plugin is displayed on the sidebar of your Bludit site:
* **Label:** The header for the plugin. Appears directly above the list of tracks
* **Username:** Your Last.FM Username. Used to grab your recent songs. Will not work if this field is empty.
* **Count:** The number of songs to list. Must be a value between 1 and 50.
* **Time:** How far to go back. Has 5 presets of the last 7, 30, 90, 180, and 365 days.

This plugin makes use of the "[Simple HTML DOM Parser](http://simplehtmldom.sourceforge.net/)" on Sourceforge, which has been modified slightly to prevent images being downloaded from the Last.FM page.

The main code was based off an existing piece of code that generates an RSS feed of recently played tracks, which can be found [on GitHub](https://github.com/xiffy/lastfmrss).

### TODO:
* Cache the data so that the Last.FM page is not downloaded every single time a page is loaded on your website.
* Add more options, such as recently played tracks (to compliment the most played tracks)
