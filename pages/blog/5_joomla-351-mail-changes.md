---
author: 'Michael Babker'
category: 'Blog'
publish_up: '2016-04-06 11:30:00'
date_modified: '2016-04-06 11:30:00'
title: 'Joomla! 3.5.1 Mail Changes'
alias: 'joomla-351-mail-changes'
image: ~
previous: 'final-thoughts-joomla-organizational-restructure'
next: 'joomla-36-cache-changes'
---

<p>Joomla! 3.5.1 was released yesterday and reports have been coming in regarding new errors from the mail API. While this is annoying, there is a strong reason why this needed to happen and the repercussions of it.</p>
<h3>Opportunistic TLS</h3>
<p>PHPMailer 5.2.10 introduced a new "Opportunistic TLS" feature which attempts to upgrade a SMTP mail connection to use TLS security if it is detected from the SMTP hello connection's response that the server reports support for it. Unfortunately this seems somewhat buggy and causes mail sending to fail unexpectedly. 3.5.1 introduces a fallback mechanism that will attempt to send a message again after disabling this feature if it was turned on.</p>
<h3>Error Logging</h3>
<p>PHPMailer supports error callbacks which allows a processor to handle errors in an appropriate manner. Joomla! 3.5.1 adds a processor which will log messages from the PHPMailer API using the JLog API, these messages use the "mail" category.</p>
<h3>Exposed Errors</h3>
<p>As part of the changes dealing with the Opportunistic TLS feature, PHPMailer is now configured to throw phpmailerExceptions instead of returning boolean false on error conditions. This change exposes new errors in how the core CMS and extensions used the mail API. One example is in <a href="https:/github.com/joomla/joomla-cms/pull/9577" rel="nofollow">this pull request</a> which corrected the wrong use of the <code>JMail::addReplyTo()</code> method.</p>
<h3>Dealing With phpmailerExceptions</h3>
<p>There are two options to deal with these changes. If you have written an extension which uses the mail API and really do not want it to throw phpmailerExceptions then you'll need to instantiate a JMail object configured to not do so by calling <code>$mail = new JMail(false);</code> (note this is backward compatible, Joomla! 3.5.0 and earlier did not have a constructor parameter though). Using <code>JFactory::getMailer()</code> or <code>JMail::getInstance()</code> without parameters will result in the cached singleton JMail object being returned with phpmailerExceptions being thrown.</p>
<p>The other choice is to catch phpmailerExceptions and to handle them as you would other errors. PHPMailer only throws phpmailerExceptions, so catching these in your code is more than enough.</p>
<h3>Inconsistent Return Value</h3>
<p>One final thought (note, this was added after original publishing). The return from <code>JMail::Send()</code> is not a boolean value with the failed send result. The <code>$result</code> variable is overwritten with the result of a <code>JError::raiseNotice()</code> call, which returns a <code>JException</code> object. So if scripts are checking for a boolean false return, this only happens if the mailer is set offline in the global configuration. This could give a false impression that sending did succeed. Incidentally this is documented in the <code>JMail::Send()</code> doc block but is inconsistent with the parent <code>PHPMailer::Send()</code> method.</p>