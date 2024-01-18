<!DOCTYPE html>
<!--
 *  Copyright (c) 2016 The WebRTC project authors. All Rights Reserved.
 *
 *  Use of this source code is governed by a BSD-style license
 *  that can be found in the LICENSE file in the root of the source
 *  tree.
-->
<html>
<head>

    <meta charset="utf-8">
    <meta name="description" content="WebRTC code samples">
    <meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1, maximum-scale=1">
    <meta itemprop="description" content="Client-side WebRTC code samples">
    <meta itemprop="image" content="assets/images/webrtc-icon-192x192.png">
    <meta itemprop="name" content="WebRTC code samples">
    <meta name="mobile-web-app-capable" content="yes">
    <meta id="theme-color" name="theme-color" content="#ffffff">

    <base target="_blank">

    <title>captureStream(): video to video</title>

    <link rel="icon" sizes="192x192" href="assets/images/webrtc-icon-192x192.png">
    <link href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="assets/css/vid-stream.css"/><!-- ../../../css/main.css------>
    <link rel="stylesheet" href="assets/css/vid-stream-1.css"/><!----- css/main.css--->

</head>

<body>

<div id="container">

    <h1><a href="//webrtc.github.io/samples/" title="WebRTC samples homepage">WebRTC samples</a> <span>captureStream(): video to video</span>
    </h1>

    <video id="leftVideo" playsinline controls loop muted>
        <source src="assets/video/chrome.webm" type="video/webm"/>
        <source src="assets/video/chrome.mp4" type="video/mp4"/>
        <p>This browser does not support the video element.</p>
    </video>

    <video id="rightVideo" playsinline autoplay></video>

    <p>Press play on the left video to start the demo.</p>

    <p>A stream is captured from the video element on the left using its <code>captureStream()</code> method and set as
        the <code>srcObject</code> of the video element on the right.</p>

    <p>The <code>stream</code> variable are in global scope, so you can inspect them from the browser console.</p>

    <a href="https://github.com/webrtc/samples/tree/gh-pages/src/content/capture/video-video"
       title="View source for this page on GitHub" id="viewSource">View source on GitHub</a>

</div>

<script src="assets/js/main.js"></script>
<script src="assets/js/lib/ga.js"></script>
</body>
</html>