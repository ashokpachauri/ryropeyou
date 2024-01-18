<!DOCTYPE html>
<!--
 *  Copyright (c) 2015 The WebRTC project authors. All Rights Reserved.
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


    <title>Peer to Peer Video Call</title>
    <link rel="icon" sizes="192x192" href="assets/images/webrtc-icon-192x192.png">
    <link href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="assets/css/vid-stream.css"/>
    <link rel="stylesheet" href="assets/css/video-call.css"/>

    <!-- Load the polyfill to switch-hit between Chrome and Firefox -->
    <style>
        video {
            border: 5px solid black;
            width: 320px;
            height: 240px;
        }
    </style>
</head>
<body>
<div id="container">

    <h1><a href="" title="WebRTC samples homepage">RY - Communications</a> <span>VideoCall</span>
    </h1>

    <video style="width:48%;" id="vid1" playsinline autoplay muted></video>
    <video style="width:48%;" id="vid2" playsinline autoplay></video>
    <br>
    <button id="callButton">Call</button>
    <button id="acceptButton">Accept</button>
    <button id="hangUpButton">Hang Up</button>
</div>
<script src="https://webrtc.github.io/adapter/adapter-latest.js"></script>
<script src="assets/js/video-call.js" async></script>
<script src="assets/js/lib/ga.js"></script>
</body>
</html>

