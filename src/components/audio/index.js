import WaveSurfer from 'wavesurfer.js'
import {Howl, Howler} from 'Howler/dist/howlerCustom.js'
let previousButton = null
let visualizerButton = null
let wavesurfer = null
let audio = null
let url = null
let prevWaveUrl = null
let pronounciationAudio = ['pronounciationAudio1', 'pronounciationAudio2', 'pronounciationAudio3', 'pronounciationAudio4',
  'pronounciationAudio5', 'pronounciationAudio6', 'pronounciationAudio7', 'pronounciationAudio8', 'pronounciationAudio9',
  'pronounciationAudio10', 'pronounciationAudio11', 'pronounciationAudio12', 'pronounciationAudio13', 'pronounciationAudio14',
  'pronounciationAudio15', 'pronounciationAudio16', 'pronounciationAudio17', 'pronounciationAudio18', 'pronounciationAudio19',
  'pronounciationAudio20', 'pronounciationAudio21', 'pronounciationAudio22', 'pronounciationAudio23', 'pronounciationAudio24',
  'pronounciationAudio25', 'pronounciationAudio26', 'pronounciationAudio27', 'pronounciationAudio28', 'pronounciationAudio29']
let pauseButton = ['testSpanishPauseButton', 'testGrammarAudioPauseButton', 'testEnglishPauseButton', 'testGrammarPauseButton']
let repeatButtonsTest = ['testSpanishButtonRepeat', 'testEnglishButtonRepeat', 'testGrammarButtonRepeat', 'testGrammarAudioButtonRepeat']
let testButtons = ['testSpanishButton', 'testEnglishButton', 'testGrammarButton', 'testGrammarAudioButton']
let repeatButtons = ['lessonAudioPlayButtonRepeat', 'grammarAudioPlayButtonRepeat', 'testSpanishButtonRepeat',
  'testEnglishButtonRepeat', 'testGrammarButtonRepeat', 'testGrammarAudioButtonRepeat', 'grammarRepeatAudioPlayButton', 'cultureRepeatAudioPlayButton',
  'syllabusRepeatAudioPlayButton', 'sentencePopupRepeatAudio0PlayButton', 'sentencePopupRepeatAudio1PlayButton',
  'sentencePopupRepeatAudio2PlayButton', 'sentencePopupRepeatAudio3PlayButton', 'sentencePopupRepeatAudio4PlayButton',
  'sentencePopupRepeatAudio5PlayButton', 'sentencePopupRepeatAudio6PlayButton', 'sentencePopupRepeatAudio7PlayButton',
  'sentencePopupRepeatAudio8PlayButton', 'sentencePopupRepeatAudio9PlayButton', 'wordPopupRepeatAudio0PlayButton',
  'wordPopupRepeatAudio1PlayButton', 'wordPopupRepeatAudio2PlayButton', 'wordPopupRepeatAudio3PlayButton',
  'wordPopupRepeatAudio4PlayButton', 'wordPopupRepeatAudio5PlayButton', 'wordPopupRepeatAudio6PlayButton',
  'wordPopupRepeatAudio7PlayButton', 'wordPopupRepeatAudio8PlayButton', 'wordPopupRepeatAudio9PlayButton',
  'basicAudioRepeat1PlayButton', 'basicAudioRepeat2PlayButton', 'basicAudioRepeat3PlayButton', 'basicAudioRepeat4PlayButton',
  'basicAudioRepeat5PlayButton', 'basicAudioRepeat6PlayButton', 'basicAudioRepeat7PlayButton', 'basicAudioRepeat8PlayButton',
  'basicAudioRepeat9PlayButton', 'basicAudioRepeat10PlayButton', 'basicAudioRepeat11PlayButton', 'basicAudioRepeat12PlayButton',
  'basicAudioRepeat13PlayButton', 'basicAudioRepeat14PlayButton', 'basicAudioRepeat15PlayButton', 'basicAudioRepeat16PlayButton',
  'basicAudioRepeat17PlayButton', 'basicAudioRepeat18PlayButton', 'basicAudioRepeat19PlayButton', 'basicAudioRepeat20PlayButton',
  'basicAudioRepeat21PlayButton', 'basicAudioRepeat22PlayButton', 'basicAudioRepeat23PlayButton', 'basicAudioRepeat24PlayButton',
  'basicAudioRepeat25PlayButton', 'basicAudioRepeat26PlayButton', 'basicAudioRepeat27PlayButton', 'basicAudioRepeat28PlayButton',
  'basicAudioRepeat29PlayButton', 'basicAudioRepeat30PlayButton', 'basicAudioRepeat31PlayButton', 'basicAudioRepeat32PlayButton',
  'basicAudioRepeat33PlayButton', 'basicAudioRepeat34PlayButton', 'basicAudioRepeat35PlayButton', 'basicAudioRepeat36PlayButton',
  'basicAudioRepeat37PlayButton', 'basicAudioRepeat38PlayButton', 'basicAudioRepeat39PlayButton', 'basicAudioRepeat40PlayButton',
  'basicAudioRepeat41PlayButton', 'basicAudioRepeat42PlayButton', 'basicAudioRepeat43PlayButton', 'basicAudioRepeat44PlayButton',
  'basicAudioRepeat45PlayButton', 'basicAudioRepeat46PlayButton', 'basicAudioRepeat47PlayButton', 'basicAudioRepeat48PlayButton',
  'basicAudioRepeat49PlayButton', 'basicAudioRepeat50PlayButton', 'basicAudioRepeat51PlayButton', 'basicAudioRepeat52PlayButton',
  'basicAudioRepeat1PlayButtonMobile', 'basicAudioRepeat2PlayButtonMobile', 'basicAudioRepeat3PlayButtonMobile', 'basicAudioRepeat4PlayButtonMobile',
  'basicAudioRepeat5PlayButtonMobile', 'basicAudioRepeat6PlayButtonMobile', 'basicAudioRepeat7PlayButtonMobile', 'basicAudioRepeat8PlayButtonMobile',
  'basicAudioRepeat9PlayButtonMobile', 'basicAudioRepeat10PlayButtonMobile', 'basicAudioRepeat11PlayButtonMobile', 'basicAudioRepeat12PlayButtonMobile',
  'basicAudioRepeat13PlayButtonMobile', 'basicAudioRepeat14PlayButtonMobile', 'basicAudioRepeat15PlayButtonMobile', 'basicAudioRepeat16PlayButtonMobile',
  'basicAudioRepeat17PlayButtonMobile', 'basicAudioRepeat18PlayButtonMobile', 'basicAudioRepeat19PlayButtonMobile', 'basicAudioRepeat20PlayButtonMobile',
  'basicAudioRepeat21PlayButtonMobile', 'basicAudioRepeat22PlayButtonMobile', 'basicAudioRepeat23PlayButtonMobile', 'basicAudioRepeat24PlayButtonMobile',
  'basicAudioRepeat25PlayButtonMobile', 'basicAudioRepeat26PlayButtonMobile', 'basicAudioRepeat27PlayButtonMobile', 'basicAudioRepeat28PlayButtonMobile',
  'basicAudioRepeat29PlayButtonMobile', 'basicAudioRepeat30PlayButtonMobile', 'basicAudioRepeat31PlayButtonMobile', 'basicAudioRepeat32PlayButtonMobile',
  'basicAudioRepeat33PlayButtonMobile', 'basicAudioRepeat34PlayButtonMobile', 'basicAudioRepeat35PlayButtonMobile', 'basicAudioRepeat36PlayButtonMobile',
  'basicAudioRepeat37PlayButtonMobile', 'basicAudioRepeat38PlayButtonMobile', 'basicAudioRepeat39PlayButtonMobile', 'basicAudioRepeat40PlayButtonMobile',
  'basicAudioRepeat41PlayButtonMobile', 'basicAudioRepeat42PlayButtonMobile', 'basicAudioRepeat43PlayButtonMobile', 'basicAudioRepeat44PlayButtonMobile',
  'basicAudioRepeat45PlayButtonMobile', 'basicAudioRepeat46PlayButtonMobile', 'basicAudioRepeat47PlayButtonMobile', 'basicAudioRepeat48PlayButtonMobile',
  'basicAudioRepeat49PlayButtonMobile', 'basicAudioRepeat50PlayButtonMobile', 'basicAudioRepeat51PlayButtonMobile', 'basicAudioRepeat52PlayButtonMobile']

function checkIfButtonExist(button){
  for (let i = 0; i < repeatButtons.length; i++) {
    if(repeatButtons[i] === button){
      return true
    }
  }
  return false
}

export function playerHowler(url, id){
  var emptyAudioArray = ['http://api.talkfluentspanish.com/api/publicnull',
    'http://api.talkfluentspanish.com/api/publicNONE',
    'http://api.talkfluentspanish.com/api/publicnone',
    'http://api.talkfluentspanish.com/api/public',
    'https://api.talkfluentspanish.com/api/publicnull',
    'https://api.talkfluentspanish.com/api/publicNONE',
    'https://api.talkfluentspanish.com/api/publicnone',
    'https://api.talkfluentspanish.com/api/public']
  if(previousButton !== null){
    changeToInitial(previousButton)
  }
  changeToSpinning(id)
  if(parseInt(id) === 0){
    if(previousButton !== null){
      makeInActive(previousButton)
      previousButton = null
    }
  }else if(parseInt(id) === 1){
    if(previousButton !== null){
      makeInActive(previousButton)
      previousButton = null
    }
  }else{
    if(previousButton === null){
      makeActive(id)
      previousButton = id
      if(visualizerButton !== null){
        makeInActive(visualizerButton)
        visualizerButton = null
      }
    }else{
      if(previousButton === id && checkIfButtonExist(previousButton) || previousButton === id){
        //
      }else{
        makeActive(id)
        makeInActive(previousButton)
        previousButton = id
        if(visualizerButton !== null){
          makeInActive(visualizerButton)
          visualizerButton = null
        }
      }
    }
  }
  if(emptyAudioArray.indexOf(url) === -1){
    if(audio !== null){
      audio.unload()
    }
    if(wavesurfer !== null){
      // wavesurfer.pause()
      wavesurfer.un('finish', null)
      wavesurfer.setVolume(0)
    }
    if(id !== 1){
      audio = new Howl({
        src: url,
        html5: true,
        buffer: true,
        onplay: function(){
          changeToInitial(id)
        },
        onwait: id
      })
      // console.log(audio)
      // audio.seek(0)
      audio.play()
      if(checkIfButtonExist(id)){
        // audio.onend = function(){
          // audio.play()
        // }
        audio.on('end', function(){
          // playerHowler(url, id)
          // audio.seek(0)
          audio.play()
        })
      }
    }else if(id === 1){
      window.setTimeout(function(){
        initVisualizer(url)
      }, 1000)
    }
  }else{
    if(previousButton !== null){
      makeInActive(previousButton)
    }else{
      //
    }
    pause()
    wavesurfer = null
    previousButton = null
    visualizerButton = null
    var waveSurferFlag = document.getElementById('mp3_player')
    waveSurferFlag.style.visibility = 'hidden'
    alert('No Audio Found!')
  }
}

function changeToSpinning(id){
  if(testButtons.indexOf(id) >= 0){
    $('#' + id).removeClass('fa fa-play')
    $('#' + id).addClass('fas fa-circle-notch fa-spin')
  }else if(repeatButtons.indexOf(id) === -1){
    // implement remove class here using ios, if this is implemented it affects the wholle process
    // implement only on the IPA
    // findout why sub component not working with removeclass while wokring on javascript style
    $('#' + id).removeClass('fa fa-volume-up')
    $('#' + id).addClass('fas fa-circle-notch fa-spin')
  }else{
    $('#' + id).removeClass('fas fa-sync')
    $('#' + id).addClass('fas fa-circle-notch fa-spin')
  }
}

function changeToInitial(id){
  if(testButtons.indexOf(id) >= 0){
    $('#' + id).removeClass('fas fa-circle-notch fa-spin')
    $('#' + id).addClass('fa fa-play')
  }else if(repeatButtons.indexOf(id) === -1){
    $('#' + id).removeClass('fas fa-circle-notch fa-spin')
    $('#' + id).addClass('fa fa-volume-up')
  }else{
    $('#' + id).removeClass('fas fa-circle-notch fa-spin')
    $('#' + id).addClass('fas fa-sync')
  }
}

function initVisualizer(url){
  var progressFlag = document.getElementById('spinnerFlag')
  var waveSurferFlag = document.getElementById('mp3_player')
  $('#visualizerAudioPlayButton').removeClass('fa fa-volume-up')
  $('#visualizerAudioPlayButton').addClass('fas fa-circle-notch fa-spin')
  // progressFlag.style.display = 'block'
  waveSurferFlag.style.visibility = 'hidden'
  var parameter = {
    container: '#mp3_player',
    waveColor: '#1caceb',
    progressColor: '#1caceb',
    hideScrollbar: true,
    height: 128,
    backend: 'MediaElement'
  }
  prevWaveUrl = url
  if(wavesurfer === null){
    wavesurfer = WaveSurfer.create(parameter)
  }else{
    wavesurfer.destroy()
    wavesurfer = WaveSurfer.create(parameter)
  }
  wavesurfer.load(url)
  wavesurfer.on('ready', function() {
    window.setTimeout(function(){
      $('#visualizerAudioPlayButton').removeClass('fas fa-circle-notch fa-spin')
      $('#visualizerAudioPlayButton').addClass('fa fa-volume-up')
      waveSurferFlag.style.visibility = 'visible'
    }, 100)
    window.setTimeout(function(){
      // progressFlag.style.display = 'none'
      wavesurfer.play(0)
      wavesurfer.drawer.progress(0)
    }, 100)
  })
}

export function visualizerPlay(id){
  if(wavesurfer !== null){
    wavesurfer.setVolume(1)
    wavesurfer.play(0)
    wavesurfer.drawer.progress(0)
    let iDevices = [
      'iPad Simulator',
      'iPhone Simulator',
      'iPod Simulator',
      'iPad',
      'iPhone',
      'iPod'
    ]
    let deviceFlag = false
    if (navigator.platform) {
      while (iDevices.length) {
        if (navigator.platform === iDevices.pop()){
          // console.log('ios')
          deviceFlag = true
        }
      }
    }
    if(deviceFlag === true){
      changeToSpinning(id)
      var waveSurferFlag = document.getElementById('mp3_player')
      waveSurferFlag.style.visibility = 'hidden'
      wavesurfer.load(prevWaveUrl)
      wavesurfer.on('ready', function() {
        window.setTimeout(function(){
          // progressFlag.style.display = 'none'
          changeToInitial(id)
          waveSurferFlag.style.visibility = 'visible'
          wavesurfer.play(0)
          wavesurfer.drawer.progress(0)
        }, 100)
      })
    }else{
      if(id === 'visualizerRepeatAudioPlayButton'){
        makeActive('visualizerRepeatAudioPlayButton')
        makeInActive('visualizerAudioPlayButton')
        visualizerButton = 'visualizerRepeatAudioPlayButton'
        wavesurfer.on('finish', function(){
          wavesurfer.play(0)
          wavesurfer.drawer.progress(0)
        })
      }else{
        makeActive('visualizerAudioPlayButton')
        makeInActive('visualizerRepeatAudioPlayButton')
        visualizerButton = 'visualizerAudioPlayButton'
        // wavesurfer.backend.un('finish', null)
        wavesurfer.un('finish', null)
      }
    }
    // wavesurfer.backend.on('audioprocess', function (time){
    //    wavesurfer.drawer.progress(wavesurfer.backend.getPlayedPercents())
    //    wavesurfer.fireEvent('audioprocess', time)
    // })
  }
  if(previousButton !== null){
    makeInActive(previousButton)
    previousButton = id
  }
  if(audio !== null){
    audio.unload()
  }
}

export function pause(){
  if(audio !== null){
    audio.pause()
    makeInActive(previousButton)
  }
  if(wavesurfer !== null){
    wavesurfer.pause()
    makeInActive(previousButton)
  }
  previousButton = null
}

export function pauseHowler(urlP, id){
  if(previousButton === null){
    previousButton = id
    makeActive(previousButton)
  }else if(previousButton !== id){
    makeInActive(previousButton)
    makeActive(id)
    previousButton = id
  }
  if(audio !== null && url === urlP){
    audio.pause()
  }else if(audio !== null && url !== urlP && (pauseButton.indexOf(id) > -1)){
    audio.pause()
    audio.unload()
    audio = null
    makeInActive(previousButton)
  }
}

export function playHowler(urlP, id){
  if(audio === null){
    url = urlP
    playerHowler(urlP, id)
  }else{
    if(urlP === url){
      makeInActive(previousButton)
      makeActive(id)
      if(pauseButton.indexOf(previousButton) > -1){
        audio.play()
        if(repeatButtonsTest.indexOf(id) > -1){
          audio.on('end', function(){
            // audio.seek(0)
            audio.play()
          })
        }else{
          audio.off('end')
        }
      }else if(testButtons.indexOf(previousButton) > -1 && repeatButtonsTest.indexOf(id) > -1 && audio.playing() === false){
        audio.seek(0)
        audio.play()
        audio.on('end', function(){
          // audio.seek(0)
          audio.play()
        })
      }else if(testButtons.indexOf(previousButton) > -1 && testButtons.indexOf(id) > -1 && audio.playing() === false){
        audio.seek(0)
        audio.play()
      }else{
        if(repeatButtonsTest.indexOf(id) > -1){
          audio.on('end', function(){
            // audio.seek(0)
            audio.play()
          })
        }else{
          audio.off('end')
        }
      }
      previousButton = id
    }else{
      url = urlP
      audio.pause()
      audio.unload()
      audio = null
      makeInActive(previousButton)
      playerHowler(urlP, id)
    }
  }
}

function makeActive(id){
  var container = document.getElementById(id)
  // how to detect mobile screen on javascript?
  if(id === 'lessonAudioPlayButton' || id === 'lessonAudioPlayButtonRepeat' || id === 'grammarAudioPlayButton' || id === 'grammarAudioPlayButtonRepeat' || pronounciationAudio.indexOf(id) > -1){
    container.style.background = '#ccc'
  }else{
    container.style.background = '#1caceb'
  }
  container.style.color = '#fff'
}

function makeInActive(id){
  var container = document.getElementById(id)
  if(container !== null){
    container.style.color = '#1caceb'
    if(id === 'lessonAudioPlayButton' || id === 'lessonAudioPlayButtonRepeat' || id === 'grammarAudioPlayButton' || id === 'grammarAudioPlayButtonRepeat' || pronounciationAudio.indexOf(id) > -1){
      container.style.background = '#fff'
    }else{
      container.style.background = 'inherit'
    }
  }
}

export function hideModal(modal){
  $(modal).modal('hide')
}

export function showModal(modal){
  $(modal).modal({
    backdrop: 'static',
    keyboard: true,
    show: true
  })
}

export function hideDashboardModal(){
  $('#dashboardModal').modal('hide')
}
