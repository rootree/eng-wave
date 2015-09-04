define(['./module'], function (services) {
    'use strict';
    return services.factory('SoundService',  ['FlashService', '$translate', function (FlashService, $translate) {

        var ICON_PLAY = 'icon-play3';
        var ICON_STOP = 'icon-stop2';
        var ICON_LOADING = 'icon-spinner2 spin';

        var STATUS_IS_PLAYING = 'playing';
        var STATUS_IS_STOPPED = 'stopped';
        var STATUS_IS_LOADING = 'loading';

        var lastSoundObject = null;

        return {
            play : function(buttonElement, soundfile) {

                if (!_.isNull(lastSoundObject)) {
                    return;/*
                    if (lastSoundObject.status == STATUS_IS_PLAYING || STATUS_IS_LOADING == lastSoundObject.status) {
                        lastSoundObject.file.stop();
                        lastSoundObject.status = STATUS_IS_STOPPED;
                        lastSoundObject.button.attr('class', ICON_PLAY);
                        //lastSoundObject.file.unload();

                        if (lastSoundObject.file._src == soundfile) {
                            return;
                        }
                    }*/
                }

                buttonElement.attr('class', ICON_LOADING);

                var lastSoundStatus = STATUS_IS_LOADING;
                var lastSoundFile = new Howl({
                    urls: [soundfile],
                    autoplay: true,
                    loop: false,
                    volume: 0.7,
                    onplay: function() {
                        lastSoundStatus = STATUS_IS_PLAYING;
                        buttonElement.attr('class', ICON_STOP);
                    },
                    onload: function() {
                        lastSoundStatus = STATUS_IS_STOPPED;
                        buttonElement.attr('class', ICON_PLAY);
                    },
                    onloaderror: function() {
                        lastSoundStatus = STATUS_IS_STOPPED;
                        buttonElement.attr('class', ICON_PLAY);
                        FlashService.error($translate.instant('MESSAGE_ERROR_ON_SOUND'));
                        console.log('Problem with: ' + soundfile);
                        lastSoundObject = null;
                    },
                    onend: function() {
                        lastSoundStatus = STATUS_IS_STOPPED;
                        buttonElement.attr('class', ICON_PLAY);
                        lastSoundObject = null;
                    }
                });

                lastSoundObject = {
                    status : lastSoundStatus,
                    file : lastSoundFile,
                    button : buttonElement
                }
            }
        };
    }]);
});
