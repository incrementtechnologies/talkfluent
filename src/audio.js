function playBasicAudio(id){
  var audio = document.getElementById(id)
  audio.currentTime = 0
  pauseAll(2)
  audio.play()
}
function pauseAll(length){
  for (var i = 1; i <= length; i++) {
    var audio = document.getElementById('basicAudio' + i)
    audio.pause()
  }
}
