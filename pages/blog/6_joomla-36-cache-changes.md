---
author: 'Michael Babker'
category: 'Blog'
publish_up: '2016-07-13 20:00:00'
date_modified: '2016-07-13 20:00:00'
title: 'Joomla! 3.6 Cache Changes'
alias: 'joomla-36-cache-changes'
image: ~
previous: 'joomla-351-mail-changes'
next: 'open-sourcing-docs-concept'
---

<p>With the release of Joomla! 3.6, additional error handling was introduced to the caching layer to help prevent issues. Unfortunately, this has exposed some misconfigured sites and has been met with calls for the change to be reverted and for Joomla to essentially discard cache layer errors silently. I'm going to explain here why the changes were made, how this is consistent with the rest of the method's error checks, and what can be done to make this error happen less frequently.</p>
<h3>The Change</h3>
<p>I submitted <a href="https://github.com/joomla/joomla-cms/pull/10339" rel="nofollow">a pull request</a> which made a change to the <code>JCacheStorage::getInstance()</code> method to check that the requested cache storage adapter is supported on the local server which validates the adapter should be safe to use. It was a partial response to <a href="https://github.com/joomla/joomla-cms/issues/9426">a bug report</a> in which a Joomla installation was using a cache adapter that was not functioning after a PHP configuration update, resulting in a PHP Fatal Error. The support check causes <code>JCacheStorage::getInstance()</code> to throw a <code>RuntimeException</code> if the adapter is not supported instead of returning an adapter which cannot function and its use could lead to other errors.</p>
<p>This error handling is consistent with other checks already in <code>JCacheStorage::getInstance()</code>, specifically how it will throw a <code>RuntimeException</code> if an invalid adapter type is provided which prevents instantiating a class that doesn't exist in PHP, another fatal error and one that can only exist due to an application misconfiguration.</p>
<h3>The Cause</h3>
<p>To trigger the new "Cache Storage is not supported on this platform" error, there must be a configuration issue present in the Joomla installation. The common issue comes from the file cache handler, which requires that the configured cache directory (either a custom path defined with the <code>cache_path</code> parameter in the configuration or the <code>cache</code> directory on the frontend or <code>administrator/cache</code> directory for the backend) is writable. If this path is not writable, the cache store will report itself as not usable and cause the <code>RuntimeException</code> to be thrown. Another potential example includes if APC(u) is configured as the cache storage adapter but the PHP extension is not installed or disabled.</p>
<h3>The Fix</h3>
<p>If you get the above error, you will need to validate your cache configuration and that the configured adapter can be used. For the filesystem adapter, this means ensuring the <code>cache</code> and <code>administrator/cache</code> (or the path defined in the <code>cache_path</code> configuration parameter) is writable. For other adapters, this generally means ensuring the PHP extension is installed, enabled, and functioning.</p>
<h3>Graceful Fallback</h3>
<p>Some have suggested that Joomla's cache API should have a graceful fallback or swallow any errors to allow the site to continue functioning. At a high level, this is a bad behavior to accept because it causes errors to be masked. This however does not mean that errors can't be caught or consumers of the cache service implement their own fallbacks. The cache API throws <code>RuntimeException</code> for "critical" failures (either the controller or storage adapters not being found, the storage adapter being unsupported, or failing to connect to a Memcache(d) instance), so consumers could wrap their cache interactions in a try/catch block to catch these exceptions and implement an error handling mechanism in the case of a major failure with the cache API.</p>