<span class="accent-item">
    <span class="accent-header-holder">
      <span class="accent-header">
        <b><label onclick="focusDiv('div1')">H</label><label onclick="focusDiv('div2')">o</label></b><label onclick="focusDiv('div3')">l</label><label onclick="focusDiv('div4')">a</label>
      </span>
    </span>
    <span class="accent-item-holder">
      <span class="accent-header-extra" id="divA">
        <label><b>Ho</b>la</label>
      </span>
      <span class="accent-header-extra-info" style="color: #cccc;margin-top:-5px;" id="divB">
        <i class="fa fa-info-circle popover_title text-primary" @click="popOverFlag = !popOverFlag"></i>Interjection
        <span v-bind:class="{'active-popover': popOverFlag === true}" class="popover_content">
          <span class="header-popover"><i class="fas fa-close" @click="popOverFlag = !popOverFlag"></i></span>
          <p>
            Lorem ipsum dolor sit amet, tempor cursus integer  anim magna, Lorem ipsum dolor sit amet, tempor cursus integer  anim magna,  copy
          </p>
        </span>  
      </span>
      
      <span class="accent-item" id="div1">
        
         <span class="accent-content">
          <span class="accent-content-left">

            <span class="accent-audio show-on-desktop">
              <i class="fa fa-volume-up small-volume-icons" @click="playerHowler('http://api.talkfluentspanish.com/api/public/storage/audio_files/1 - hola como estado ver2.mp3',  'basicAudio1PlayButton')" id="basicAudio1PlayButton"></i>
              <br>
              <i class="fas fa-sync small-volume-icons repeat-audio-icon-ios" @click="playerHowler('http://api.talkfluentspanish.com/api/public/storage/audio_files/1 Hola como has estado -ver1.mp3',  'basicAudioRepeat1PlayButton')" id="basicAudioRepeat1PlayButton" style="margin-top: 5px;"></i>
            </span>

            <span class="accent-letter">
              <label>H</label>
              <label class="bottom"><b>(silent)</b></label>
            </span>

            <span class="accent-audio show-on-mobile">
              <i class="fa fa-volume-up small-volume-icons" @click="playerHowler('http://api.talkfluentspanish.com/api/public/storage/audio_files/1 - hola como estado ver2.mp3',  'basicAudio1PlayButtonMobile')" id="basicAudio1PlayButtonMobile"></i>
              <br>
              <i class="fas fa-sync small-volume-icons repeat-audio-icon-ios" @click="playerHowler('http://api.talkfluentspanish.com/api/public/storage/audio_files/1 Hola como has estado -ver1.mp3',  'basicAudioRepeat1PlayButtonMobile')" id="basicAudioRepeat1PlayButtonMobile" style="margin-top: 5px;"></i>
            </span>

            <span class="accent-media-holder">
              <img src="http://api.talkfluentspanish.com/api/public/storage/view_images/2019-03-02_02_22_06.png" class="accent-media-item" height="100%" width="100%" data-toggle="modal" data-target="#imageView" onclick="insertImage('http://api.talkfluentspanish.com/api/public/storage/view_images/2019-03-02_02_22_06.png')">
              <div class="wistia_embed popover=true accent-media-item wistia_async_u8p9wq6mq8" onclick="pause()"></div>
            </span>
          </span>

          
          <span class="accent-letter-defi">
             <ul style="margin: 0px; padding: 0px;">
               <li><b>Consonant</b></li>
               <li><b style="color: blue;"><u>h</u></b> as in <b style="color: blue;"><u>h</u></b>onor, <b style="color: blue;"><u>h</u></b>, <b style="color: blue;"><u>h</u></b>our</li>
               <li>Spanish examples:    <b style="color: blue;"><u>h</u></b>ermosa [beautiful]   <b style="color: blue;"><u>h</u></b>umor [humor]   <b style="color: blue;"><u>h</u></b>ola [hello]</li>
               <li>IPA letter /no equivalent/:  / ɛɾ.'mo.sa /   / u.ˈmoɾ /              / 'o.la /</li>
               <li>The h consonant is always silent in Latin American Spanish, unless the word is borrowed from another language, as in the case of hámster where the <b style="color: blue;"><u>h</u></b> is voiced and aspirated (like the soft g). </li>
             </ul>  
          </span>
        </span>
      </span>

      <span class="accent-item" id="div2">
        <span class="accent-content">
          <span class="accent-content-left">

            <span class="accent-audio show-on-desktop">
              <i class="fa fa-volume-up small-volume-icons" @click="playerHowler('http://api.talkfluentspanish.com/api/public/storage/audio_files/1 Hola como has estado -ver1.mp3', 'basicAudio2PlayButton')" id="basicAudio2PlayButton"></i>
              <br>
              <i class="fas fa-sync small-volume-icons repeat-audio-icon-ios" @click="playerHowler('http://api.talkfluentspanish.com/api/public/storage/audio_files/1 Hola como has estado -ver1.mp3',  'basicAudioRepeat2PlayButton')" id="basicAudioRepeat2PlayButton" style="margin-top: 5px;"></i>
            </span>

            <span class="accent-letter">
              <label>O</label>
             <label class="bottom"><b>(silent)</b></label>
            </span>

            <span class="accent-audio show-on-mobile">
              <i class="fa fa-volume-up small-volume-icons" @click="playerHowler('http://api.talkfluentspanish.com/api/public/storage/audio_files/1 Hola como has estado -ver1.mp3', 'basicAudio2PlayButtonnMobile')" id="basicAudio2PlayButtonnMobile"></i>
              <br>
              <i class="fas fa-sync small-volume-icons repeat-audio-icon-ios" @click="playerHowler('http://api.talkfluentspanish.com/api/public/storage/audio_files/1 Hola como has estado -ver1.mp3',  'basicAudioRepeat2PlayButtonnMobile')" id="basicAudioRepeat2PlayButtonnMobile" style="margin-top: 5px;"></i>
            </span>


            <span class="accent-media-holder">
              <img src="https://api.zenithal.co/public/images/mirror/2_1_Analytics.jpg" class="accent-media-item" height="100%" width="100%" data-toggle="modal" data-target="#imageView" onclick="insertImage('https://api.zenithal.co/public/images/mirror/2_1_Analytics.jpg')">
              <div class="wistia_embed popover=true accent-media-item wistia_async_u8p9wq6mq8" @click="pause()"></div>
            </span>
          </span>
          <span class="accent-letter-defi">
             <ul style="margin: 0px; padding: 0px;">
                 <li><b>Vowel</b></li>
                 <li><b style="color: blue;"><u>o</u></b> is pronounced <i>oh</i>, as in st<b style="color: blue;"><u>o</u></b>re, f<b style="color: blue;"><u>o</u></b>rce, n<b style="color: blue;"><u>o</u></b>rth </li>
                 <li>Spanish examples:     b<b style="color: blue;"><u>o</u></b>sc<b style="color: blue;"><u>o</u></b>s<b style="color: blue;"><u>o</u></b> [wooded]   t<b style="color: blue;"><u>o</u></b>rre [tower]    <b style="color: blue;"><u>o</u></b>r<b style="color: blue;"><u>o</u></b> [gold]</li>
                 <li>IPA Letter: /o/    / bos.ˈko.so /             / ˈto.re /        / ˈo.ɾo /</li>
                 <ul>
                   <li><b>Jaw</b> - The jaw drops in a slightly tense position.</li>
                   <li><b>Lips</b> - The lips flare a bit and forms a rounded shape. The tenseness in the o vowel causes the cheeks to hollow.</li>
                   <li><b>Tongue</b> - The tongue shifts back. The tip of the tongue doesn’t touch anything.</li>
                   <li><b>Airflow</b> - The air produced passes from the lungs to the vocal tract and out of the mouth without any stops.</li>
                 </ul>
             </ul>
          </span>
        </span>
      </span>

      <span class="accent-item" id="div3">
       <span class="accent-content">
          <span class="accent-content-left">
            <span class="accent-audio show-on-desktop">
              <i class="fa fa-volume-up small-volume-icons" @click="playerHowler('http://api.talkfluentspanish.com/api/public/storage/audio_files/1 Hola como has estado -ver1.mp3', 'basicAudio3PlayButton')" id="basicAudio3PlayButton"></i>
              <br>
              <i class="fas fa-sync small-volume-icons repeat-audio-icon-ios" @click="playerHowler('http://api.talkfluentspanish.com/api/public/storage/audio_files/1 Hola como has estado -ver1.mp3',  'basicAudioRepeat3PlayButton')" id="basicAudioRepeat3PlayButton" style="margin-top: 5px;"></i>
            </span>

            <span class="accent-letter">
              <label>L</label>
            </span>

            <span class="accent-audio show-on-mobile">
              <i class="fa fa-volume-up small-volume-icons" @click="playerHowler('http://api.talkfluentspanish.com/api/public/storage/audio_files/1 Hola como has estado -ver1.mp3', 'basicAudio3PlayButtonnMobile')" id="basicAudio3PlayButtonnMobile"></i>
              <br>
              <i class="fas fa-sync small-volume-icons repeat-audio-icon-ios" @click="playerHowler('http://api.talkfluentspanish.com/api/public/storage/audio_files/1 Hola como has estado -ver1.mp3',  'basicAudioRepeat3layButtonnMobile')" id="basicAudioRepeat3layButtonnMobile" style="margin-top: 5px;"></i>
            </span>

            <span class="accent-media-holder">
              <img src="https://api.zenithal.co/public/images/mirror/2_1_Analytics.jpg" class="accent-media-item" height="100%" width="100%" data-toggle="modal" data-target="#imageView" onclick="insertImage('https://api.zenithal.co/public/images/mirror/2_1_Analytics.jpg')">
              <div class="wistia_embed popover=true accent-media-item wistia_async_u8p9wq6mq8" @click="pause()"></div>
            </span>
          </span>
          <span class="accent-letter-defi">
             <ul style="margin: 0px; padding: 0px;">
                  <li><b>Consonant</b></li>
                  <li><b style="color: blue;"><u>l</u></b> as in <b style="color: blue;"><u>l</u></b>eader, <b style="color: blue;"><u>l</u></b>eft, <b style="color: blue;"><u>l</u></b>emon</li>
                  <li>Spanish examples: <b style="color: blue;"><u>l</u></b>uz [light]   <b style="color: blue;"><u>l</u></b>ago [lake]     <b style="color: blue;"><u>l</u></b>eón [lion]</li>
                  <li>IPA letter: /l/           / ˈlus /             / 'la. ɣo /    / le.' õn /</li>
                  <ul>
                    <li>This letter sounds like the English <i>l</i>, but with the tongue raised closer to the roof of the mouth.</li>
                    <li>The <b style="color: blue;"><u>l</u></b> consonant is considered a <b><i>voiced alveolar lateral</i></b>. <b>Alveolars</b> are consonants formed by interfering with the flow of air by pressing the tip of the tongue against the skin on the back of the upper front teeth. <b>Laterals</b> are consonants formed when the air is allowed to escape through the sides of the tongue. <b>Voiced consonants</b> involve the vocal cords in order to produce sounds.</li>
                  </ul>
                  <li><b>Jaw</b> – The jaw drops about mid-way, but it can vary depending on the vowel that follows it.</li>
                  <li><b>Lips</b> – The lips part and the mouth opens.</li>
                  <li><b>Tongue</b> - The tongue tip touches that alveolar ridge, which can be found behind the upper front teeth. The back of the tongue lowers.</li>
                  <li><b>Airflow</b> - The air escapes around the sides of the tongue. The l consonant needs the vocal cords’ vibrations in order to complete the sound.</li>
             </ul>
          </span>
        </span>
      </span>

      <span class="accent-item" id="div4">
         <span class="accent-content">
          <span class="accent-content-left">

            <span class="accent-audio show-on-desktop">
              <i class="fa fa-volume-up small-volume-icons" @click="playerHowler('http://api.talkfluentspanish.com/api/public/storage/audio_files/1 Hola como has estado -ver1.mp3', 'basicAudio4PlayButton')" id="basicAudio4PlayButton"></i>
              <br>
              <i class="fas fa-sync small-volume-icons repeat-audio-icon-ios" @click="playerHowler('http://api.talkfluentspanish.com/api/public/storage/audio_files/1 Hola como has estado -ver1.mp3',  'basicAudioRepeat4PlayButton')" id="basicAudioRepeat4PlayButton" style="margin-top: 5px;"></i>
            </span>

            <span class="accent-letter">
              <label>A</label>
            </span>

            <span class="accent-audio show-on-mobile">
              <i class="fa fa-volume-up small-volume-icons" @click="playerHowler('http://api.talkfluentspanish.com/api/public/storage/audio_files/1 Hola como has estado -ver1.mp3', 'basicAudio4PlayButtonnMobile')" id="basicAudio4PlayButtonnMobile"></i>
              <br>
              <i class="fas fa-sync small-volume-icons repeat-audio-icon-ios" @click="playerHowler('http://api.talkfluentspanish.com/api/public/storage/audio_files/1 Hola como has estado -ver1.mp3',  'basicAudioRepeat4PlayButtonnMobile')" id="basicAudioRepeat4PlayButtonnMobile" style="margin-top: 5px;"></i>
            </span>

            <span class="accent-media-holder">
              <img src="https://api.zenithal.co/public/images/mirror/2_1_Analytics.jpg" class="accent-media-item" height="100%" width="100%" data-toggle="modal" data-target="#imageView" onclick="insertImage('https://api.zenithal.co/public/images/mirror/2_1_Analytics.jpg')">
              <div class="wistia_embed popover=true accent-media-item wistia_async_u8p9wq6mq8" @click="pause()"></div>
            </span>
          </span>
          <span class="accent-letter-defi">
             <ul style="margin: 0px; padding: 0px;">
               <li><b>Vowel</b></li>
               <li>
                 <b style="color: blue;"><u>a</u></b> is pronounced <i>ah</i>, as in <b style="color: blue;"><u>a</u></b>rt, b<b style="color: blue;"><u>a</u></b>rk, sp<b style="color: blue;"><u>a</u></b></li>
               <li>Spanish examples:     c<b style="color: blue;"><u>a</u></b>s<b style="color: blue;"><u>a</u></b> [house]   <b style="color: blue;"><u>a</u></b>c<b style="color: blue;"><u>a</u></b>demi<b style="color: blue;"><u>a</u></b> [academy]    s<b style="color: blue;"><u>a</u></b>ls<b style="color: blue;"><u>a</u></b> [sauce]</li>
               <li>IPA Letter: /a/  / ˈka.sa /            / a.ka.ˈde.mja /       / ˈsal.sa /</li>
               <ul>
                 <li><b>Jaw</b> - The jaw drops low and is held open.</li>
                 <li><b>Lips</b> - The lips are open and rounded, but relaxed.</li>
                 <li><b>Tongue</b> - The tongue is low and touches the bottom, side teeth.</li>
                 <li><b>Airflow</b> - The vocal tract is very open for the production of the a sound. The air passes from the lungs to the vocal tract and out of the mouth without any stops.</li>
               </ul>
             </ul>
          </span>
        </span>
      </span>
    </span>
  </span> 