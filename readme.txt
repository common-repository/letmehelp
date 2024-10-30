=== LetMeHelp - Support & Help Desk Assistant ===
Contributors:      taskotr
Author URI:        https://tarascodes.com/
Tags:              support, help desk, multi-step page, help assistant, help links
Tested up to:      6.2
Requires at Least: 6.1
Requires PHP:      7.4
Stable tag:        1.0.2
Text Domain:       letmehelp
License:           GPLv2 or later
License URI:       http://www.gnu.org/licenses/gpl-2.0.html

A WordPress plugin that streamlines the contact process by providing possible solutions to common issues, reducing support requests and improving user experience.

== Description ==

This plugin provides a simple approach to handling support requests on a WordPress website. With this plugin, you can create a form that asks users to enter a reason for their inquiry or issue.

The plugin allows you to define possible contact reasons and links to resources that address each reason.

Once a user submits their reason for contact, the plugin will search through the defined links to find possible solutions to their inquiry.

If a match is found, the plugin displays the relevant links to the user, providing quick and easy access to the information they need.

This approach helps reduce the number of support requests you receive by enabling users to find the solutions they need without having to contact you directly.

=== Advantage ===

1. **Time-saving for both website owners and visitors:** By offering potential solutions to common issues upfront, LetMeHelp helps to reduce the need for customers to contact support directly. This frees up resources that website owners can allocate elsewhere in their business while allowing visitors to receive faster answers to their support requests.
2. **Streamlined support process:** LetMeHelp's multi-step approach helps to ensure that the contact form is displayed only when necessary, preventing unnecessary support requests and enabling the support team to focus on more complex queries.
3. **Improved user experience:** Providing customers with quick access to the information they need fosters a positive perception of the brand, helping build trust and loyalty with customers, which is vital for any business.

== Installation ==

1. You have a couple options:
	* Go to Plugins &rarr; Add New and search for "LetMeHelp". Once found, click "Install".
	* Download the LetMeHelp plugin from WordPress.org and make sure the folder is zipped. Then upload it via Plugins &rarr; Add New &rarr; Upload.
2. Activate the plugin through the 'Plugins' screen in WordPress.

= Plugin configuration instrustions =

**Step 1: Accessing LetMeHelp Settings**

After installing the plugin, you can access the LetMeHelp settings in two ways:

Option A:

1. From the WordPress dashboard, locate the 'LetMeHelp' link in the main navigation menu on the side of the screen.
2. Click on the 'LetMeHelp' link to access the plugin settings.

Option B:

1. On the 'Plugins' page in WordPress, find the 'LetMeHelp' plugin.
2. Click on the 'Settings' link located under the plugin title to access the plugin settings.

**Step 2: Adding and Managing Links**

1. In the LetMeHelp settings, locate 'Add New Link' button to the 'Links' tab.
2. Click on the 'Add New Link' button to create a new link.
3. Fill in the required fields: URL (the web address of the target resource), Label (the text that will be displayed as the link), and Keywords (relevant keywords associated with the target resource).
4. Click on the 'Add' button to store the link.
5. You can edit or delete existing links by clicking on the respective 'Edit' or 'Delete' buttons for each link.

**Step 3: Creating a Multi-Step Page with the LetMeHelp Block**

1. Navigate to the WordPress 'Pages' or 'Posts' section, depending on where you want to add the multi-step contact page.
2. Click on 'Add New' to create a new page or post, or select an existing one to edit.
3. In the block editor, click on the '+' button to add a new block.
4. Search for 'LetMeHelp' in the block library and click on the 'LetMeHelp' block to insert it into your page or post.
5. Customize the Subject form and any additional text as required.
6. Add child blocks to the 'LetMeHelp' block to create the destination page. This can include a 'Group' block with a 'Contact Form' block inside, or any other desired content.

**Step 4: Previewing and Testing the Multi-Step Page**

1. In the LetMeHelp block, switch between 'Settings' and 'Preview' modes to configure and test the block's functionality.
2. Preview and test the Subject form in the 'Preview' mode.
3. Customize the Subject form and add child blocks for destination page in the 'Settings' view.
4. Once you are satisfied with the configuration, click on 'Publish' or 'Update' to save the changes and make the multi-step contact page live on your website.

== Frequently Asked Questions ==

= Explain the purpose of the LetMeHelp plugin and how it benefits WordPress users. =

LetMeHelp aims to save time for both website owners and visitors by providing potential solutions to common issues before users reach out to support. This efficiency boost allows website owners to allocate resources to other aspects of their business while ensuring visitors can quickly find the information they need, without waiting for support responses.

= In the context of the LetMeHelp plugin, what are links and how do users add them through the plugin settings? =

Links in the LetMeHelp plugin connect users to helpful resources like documentation or blog articles. To add a link, navigate to the 'Links' tab in the plugin settings and click the 'Add' button. These links serve as possible solutions to common support inquiries.

= What are the URL, label, and keywords input fields used for when adding a link in the LetMeHelp plugin? =

The URL field requires the web address of the resource you want to link to, while the label field is for the text displayed as the link. In the keywords field, add terms related to the target resource to connect the link to user queries.

= Explain the importance of keywords in the LetMeHelp plugin, and how the text input field for keywords works. =

Keywords are essential for matching user queries to relevant links. When users enter their reason for contact, the plugin searches for links with matching keywords to suggest potential solutions. If no matching keywords are found, a "not found" message is displayed, along with a link to the next page.

= How is a keyword connected to a link in the LetMeHelp plugin, and what happens when an input keyword matches or does not match a database keyword? =

Keywords are added to links when creating them in the plugin settings. If a user's input keywords match a keyword in the database, the link(s) are displayed as potential solutions. If no match is found, a "not found" message appears. In both cases, a link to the destination page is also provided.

= What is a WordPress block, and how does the LetMeHelp block function within the plugin? =

WordPress blocks are components used to build website content. The LetMeHelp block enables the creation of a multi-step page with two steps: "Reason for contact" and "destination page". The first step prompts users to enter their reason for contact, while the second step displays the contact form or any other added WordPress blocks, currently available on the website.

= How can users set custom settings for the LetMeHelp block, preview it in the editor, and test its functionality? =

The LetMeHelp block allows customization of the Subject ("Reason for contact") form and any additional text. The destination page can be customized by adding child blocks with desired content. The block also offers 'Settings' and 'Preview' modes for configuration and testing.

= What is the destination page in the LetMeHelp plugin, and how can users customize it by adding blocks and changing its appearance? =

The destination page appears after the user submits keywords and reviews potential solutions. It is possible to customize this section by adding child blocks (such as a 'Group' block containing a 'Contact Form' block) to the LetMeHelp block. Using WordPress' default settings, it is also possible to adjust the layout and appearance of the destination page to fit the needs.

== Screenshots ==

1. Screenshot of the LetMeHelp plugin's 'Add New Link' interface in the Settings page.

2. Screenshot of the LetMeHelp plugin's interface for managing existing Links on the Settings page.

3. Screenshot of the LetMeHelp block in the editor, displaying the Block Settings interface.

4. Screenshot of the LetMeHelp block in the editor, displaying the Block Preview interface.

5. Screenshot of the Subject Form: The first step of the multi-step process, where users enter their subject/reason for contacting the website.

6. Screenshot of the "Result" section: This screenshot showcases the section that appears if the user enters a keyword that matches one of the assigned links.

7. Screenshot of the "Nothing Found" section: This screenshot showcases the section that appears if the user enters a keyword that does not match any of the assigned links.

8. Screenshot of the Destionation Page: This screenshot showcases the destionation page that appears if the user is unable to find a pre-existing solution. Note, the plugin does not include contact form functionality.

== Changelog ==

= 1.0.2 - April 14, 2023 =

* Update: improve support for latest React version;
* Fix: formatting in Readme file;

= 1.0.1 - April 4, 2023 =

* Fix: keyword text case issue;
* Fix: missing custom colors in buttons;

= 1.0.0 - March 21, 2023 =

* Initial release.

= 0.9.4 - March 20, 2023 =

* Beta (4) version of the plugin.

= 0.9.3 - March 17, 2023 =

* Beta (3) version of the plugin.

= 0.9.1 - March 15, 2023 =

* Beta (2) version of the plugin.

= 0.9.0 - March 12, 2023 =

* Beta (1) version of the plugin.