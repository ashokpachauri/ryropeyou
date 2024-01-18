<table style="width:100%;">
	<tr style="width:100%;">
		<td style="width:50%;"><video id="remoteVideo" controls><source src=""/></video></td>
		<td style="width:50%;"><video id="localVideo"controls><source src=""></video></td>
	</tr>
</table>
<script>
	const localStream = await getUserMedia({video: true, audio: true});
	const peerConnection = new RTCPeerConnection(iceConfig);
	localStream.getTracks().forEach(track => {
		peerConnection.addTrack(track, localStream);
	});
	const remoteStream = MediaStream();
	const remoteVideo = document.querySelector('#remoteVideo');
	remoteVideo.srcObject = remoteStream;

	peerConnection.addEventListener('track', async (event) => {
		remoteStream.addTrack(event.track, remoteStream);
	});
</script>