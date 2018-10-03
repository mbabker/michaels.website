---
author: 'Michael Babker'
category: 'Blog'
publish_up: '2018-10-03 00:00:00'
date_modified: '2018-10-03 00:00:00'
title: 'Open Sourcing Docs Concept'
alias: 'open-sourcing-docs-concept'
image: 'binary.jpg'
previous: 'joomla-36-cache-changes'
next: ~
---

<p>For various reasons I don't really feel like getting into right now, I've honestly been feeling a fair bit burned out on Open Source contributions or just coding outside work in general lately, and as a result a lot of ideas and concepts I've started are just going nowhere. In the interest of sharing though, in the off chance there might be interest from someone in picking up on what was started, I've open sourced one of the concepts I started on; an upgraded version of the Joomla! API Documentation website, available at <a href="https://github.com/mbabker/api-docs">https://github.com/mbabker/api-docs</a>.</p>
<h3>The Purpose</h3>
<p>One of Joomla's weak points has always been in developer oriented documentation. The <a href="https://docs.joomla.org/">docs wiki</a> generally does a good job providing end user facing documentation, but there has consistently been a lack of good documentation on how to use the code. Inspired heavily by the <a href="https://developer.wordpress.org/">WordPress Code Reference</a>, the aim of this project was to convert the existing static <a href="https://api.joomla.org/">API Documentation</a> site into a PHP application to improve the doc references for automatically generated output.</p>
<p>Unlike the WordPress site, which is able to create one consistent output, the Joomla site needs to handle multiple software packages (the CMS and Framework) and multiple version branches for those packages. An issue in the existing output is that each package and version branch is in its own little world as a standalone project, there isn't any cross-linking across projects or versions so you can't follow a class across versions or in the case of Framework packages shipped with the CMS the documentation for those classes gets duplicated into both the CMS and Framework projects. Additionally, with the on-going namespacing efforts in the Joomla API, there is no documented place to see class name aliases to map the old global namespace class name to newer namespaced classes and the newer app's data model has support for working with this aliasing layer.</p>
<p>One of the ideas I had for future iterations of the site, similar to the WordPress site or the PHP documentation, could include support for user comments and feedback, allowing individuals to share helpful hints or examples on use of an API. But, this is not part of what should be considered the MVP for completing or launching this build.</p>
<h3>Current Status</h3>
<p>In its current state, a large bulk of the data model is in place. Storage and relations for the class name aliases hadn't been started yet, so that would need to be addressed. As far as doc blocks go, only the <code>@param</code> and <code>@deprecated</code> tags and the summary/description are being parsed right now, so the parser and storage would need to be updated to include the full dataset from here.</p>
<p>No user interface has been started yet, I focused primarily on the data model and the backend tooling for getting that in place in the first steps and planned to move on to the UI once that was in a suitable state. I didn't have any real plan for the UI yet other than looking at existing resources (such as the existing API Documentation site, the WordPress Code Reference, and PHP documentation) and building based on something I liked and felt would be user friendly.</p>
<h3>If Interested...</h3>
<p>If there is anyone seriously interested in working on this, go for it. The code's all in the open, GPL licensed, and copyright already attributed to Open Source Matters, Inc. (Joomla's "parent" not-for-profit) as this was designed to replace the existing api.joomla.org subdomain. Otherwise, at least the idea's out there.</p>
