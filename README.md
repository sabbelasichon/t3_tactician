[![Build Status](https://travis-ci.org/sabbelasichon/swiftmailer_single_recipient.png)](https://travis-ci.org/sabbelasichon/swiftmailer_single_recipient)

How to Work with Emails during Development
==========================================

When developing an application which sends email, you will often not want to actually send the email to the specified recipient during development.

Sending to a Specified Address(es)
----------------------------------
You can configure via the extension configuration to send all email to a one ore more specific addresses.

Swift Mailer will add an extra header to the email, X-Swift-To, containing the replaced address, so you can still see who it would have been sent to.

Sending to a Specified Address but with Exceptions
--------------------------------------------------
You can also configure the extension to allow some exceptions, so the email will be really sent to the recipient if the address is whitelisted.

