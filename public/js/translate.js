/**
 * Defines the main routes in the application.
 * The routes you see here will be anchors '#/' unless specifically configured otherwise.
 */

define(['./app'], function (app) {
    'use strict';
    return app.config(['$translateProvider', function ($translateProvider) {

        $translateProvider.translations('ru', {
            BUTTON_SEND_MESSAGE: 'Отправить сообщение',
            BUTTON_SEND_AGAIN: 'Написать еще',
            BUTTON_REGISTRATION: 'Зарегистрироваться',

            LABEL_YOUR_MESSAGE: 'Ваше сообщение:',
            LABEL_YOUR_EMAIL: 'Email адрес:',
            LABEL_YOUR_NAME: 'Ваше имя:',
            LABEL_PASSWORD: 'Пароль:',
            LABEL_PASSWORD_REPEAT: 'Пароль повторно:',

            HEADER_FEEDBACK_FORM: 'Форма обратной связи',
            HEADER_REGISTRATION_FORM: 'Форма регистрации',
            HEADER_FEEDBACK: 'Обратная связь',
            HEADER_REGISTRATION: 'Регистрация пользователя',
            HEADER_FEEDBACK_SENT: 'Запрос отправлен',

            TEXT_FEEDBACK: 'Если у вас есть вопросы, пожелания или другая информация для обсуждения, вы можете написать нам, и мы свяжемся с вами в ближайщее время.',
            TEXT_FEEDBACK_SENT: 'Ваш запрос был успешно отправлен. Вы можете отправить новый запрос при желании.',
            TEXT_REGISTRATION_HEADER: 'Как только вы пройдете регистрацию, вы будете иметь полный достук с сервису.',

            TITLE: 'Hello'
        });
        $translateProvider.translations('de', {

        });
        $translateProvider.preferredLanguage('ru');

    }]);
});


