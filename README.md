# wp-gatsby-tghp - TGHP Extensions to [wp-gatsby](https://github.com/gatsbyjs/wp-gatsby)
Common extensions for TGHP projects to the wp-gatsby plugin. While wp-gatsby optimizes your WordPress site to work as a 
data source for Gatsby, wp-gatsby-tghp adds additional layers to further optimize for our projects.

## OK, what does it actually do?
### 1. Support further internal invents to trigger wp-gatsby Actions
Actions in wp-gatsby are used to track when something has changed in WordPress so that Gatsby can tell
what needs to be rebuilt. This plugin adds a few more actions to support our internal needs:

1. Posts assigned to terms
2. Metabox (metabox.io only) field values changed on posts/terms/settings-pages
3. SimpleCustomOrder post order changed
4. Yoast SEO field values changed

### 2. Add manual build trigger
Add a handy button to the dashboard to trigger a build right now.

### 3. Track Gatsby Cloud events
Webhooks must be added to Gatsby cloud, but once added, the plugin records what happens in Gatsby cloud.
Mainly useful for tracking when successful/failed builds happen. These events are fed back to the user
via a widget on the dashboard.

