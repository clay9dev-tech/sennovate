Tabbed Content
==============

This plugin installs a custom block called **Tabbed Content**. This block does nothing but contain **Tab** blocks, which can then contain anything. When rendered, the Tabbed Content block will have a set of "Tab" buttons at the top which correspond to the block's children.

This plugin also includes enough basic css and javascript to only show the selected tab's content, but it's pretty no frills. Still, it's generic enough that you should be able to modify it from your theme css without too much work.

**How to Use**  
Just activate the plugin. You’ll then be able to add the Tabbed Content block in the block editor just like any other block. You add "Tabbed Content Tab" blocks to that, and those contain your content. The tabs themselves are added to the top of the block automatically, with labels of "Tab 1", "Tab 2", "Tab 3" and so on. 

**How to Customize**  
CSS should do most of the heavy lifting here. By default it does hide everything but the first tab and then only displays the selected tab's content when it's clicked on, but there's no transition or anything.

To change the tab labels, use the Rename feature in the block menu to set a name for your Tabbed Content Tab blocks, that custom name will automatically be copied into the respective generated Tab. You can also rename the main Tabbed Content block to set an `aria-label` attribute on the tablist element. This won't actually show up anywhere by default, but you can use CSS to add this text into a pseudo-element if you need, and it's just good accessibility practice!

There are a handful of javascript hooks that might be useful though:

* `tabbedContent.tabs` is a filter applied to the array of strings used as the Tab labels. This is done in the block editor at edit time, and doesn't affect the front end.
* `tabbedContent.beforeDeactivateTab` when activating a tab, the system will first _deactivate_ the one being switched away from, and this action will fire just before that happens, with the tab being deactivated as an argument.
* `tabbedContent.afterDeactivateTab` this action fires just _after_ the tab being switched away from is deactivated.
* `tabbedContent.beforeActivateTab` is an action that fires after a tab button is clicked, but just before the corresponding content is displayed.
* `tabbedContent.afterActivateTab` is an action that fires just after the content is displayed.

