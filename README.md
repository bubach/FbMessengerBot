# Messenger Bot for customer service

[![Deploy](https://www.herokucdn.com/deploy/button.svg)](https://heroku.com/deploy)

A starterpack for building messenger bots for customer support.
Demo: http://ng.ridestore.com

It's using wit.ai for natural language processing. So you need to setup a free account at wit.ai.
You can see our wit.ai setup here: https://wit.ai/Linushellberg/ridestorebot

You need to add a messenger contact button to your page.
And also a "send to messenger" button on the order confirmation page, to link the facebook user with the current order. See plugins here: https://developers.facebook.com/docs/messenger-platform/plugin-reference


## heroku config

|VALIDATION_TOKEN|Webhook token|
|PAGE_ACCESS_TOKEN|Your Facebook page access token|
