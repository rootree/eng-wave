/**
 * Defines the main routes in the application.
 * The routes you see here will be anchors '#/' unless specifically configured otherwise.
 */

define(['./app'], function (app) {
    'use strict';
    return app.config(['$translateProvider', function ($translateProvider) {

        $translateProvider.translations('ru', {

            // Common
            RESPONSE_CODE_1000: 'Переданы не коректные параметры',
            RESPONSE_CODE_1001: 'Произошла голическая ошибка, мы проинформированы о ней',
            RESPONSE_CODE_1002: 'Был получен пустой запрос',
            RESPONSE_CODE_1003: 'Произошла внутрянная ошибка',
            RESPONSE_CODE_1004: 'Запрошенный ресурс не найден',
            RESPONSE_CODE_1005: 'Требуется повторная авторизация',

            // Word
            RESPONSE_CODE_7000: 'Запрошенное слово не найдено',
            RESPONSE_CODE_7001: 'Произносимое слово находится в процессе подготовки',

            // Strategy
            RESPONSE_CODE_6000: 'Запрошенная стратегия не найдена',
            RESPONSE_CODE_6001: 'Стратегия не содержет элементы',

            // Package
            RESPONSE_CODE_5000: 'Запрошеный пакет слов не найден',
            RESPONSE_CODE_5001: 'Пакет уже был установлен',

            // Download
            RESPONSE_CODE_3000: 'Запрошенная закгрузка не найдена',

            // Group
            RESPONSE_CODE_4000: 'Группа не найдена',
            RESPONSE_CODE_4001: 'Выбранная группа последняя, и не может быть удалена',
            RESPONSE_CODE_4002: 'Выбранная группа содержит слова и не может быть удалена',

            // Auth
            RESPONSE_CODE_2000: 'Пользователь с указаным адресом уже зарегестрирован',
            RESPONSE_CODE_2001: 'Авторизация провалена',

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
            HEADER_TUTORIAL: 'Подсказка',

            TEXT_FEEDBACK: 'Если у вас есть вопросы, пожелания или другая информация для обсуждения, вы можете написать нам, и мы свяжемся с вами в ближайщее время.',
            TEXT_FEEDBACK_SENT: 'Ваш запрос был успешно отправлен. Вы можете отправить новый запрос при желании.',
            TEXT_REGISTRATION_HEADER: 'Как только вы пройдете регистрацию, вы будете иметь полный достук с сервису.',

            MESSAGE_DOWNLOAD_QUEUE: 'Новая загрузка поставлена в очередь для наполнения',
            MESSAGE_DOWNLOAD_DELETED: 'Загрузка удалена',
            MESSAGE_AUTH_COMPLETED: 'Авторизация уже была пройдёна',
            MESSAGE_PACKAGE_INSTALLED: 'Пакет был успешно установлен',
            MESSAGE_REG_PASSWORD_MISMATCH: 'Проверте пароль или его повторение.',
            MESSAGE_REG_COMPLETED: 'Регистарция произведена.',
            MESSAGE_SETTING_SAVED: 'Изменения зафиксированы.',
            MESSAGE_STRATEGY_DELETED: 'Выбранная стратегия удалена',
            MESSAGE_STRATEGY_SAVED: 'Стратегия обновлена',
            MESSAGE_SUPPORT_SENT: 'Ваше сообщение отправлено.',
            MESSAGE_WORD_AUTH: 'Пройдите авторизацию',
            MESSAGE_WORD_DELETED: 'Слово удалено',
            MESSAGE_WORD_ADDED: 'Новое слово успешно добавлено',
            MESSAGE_WORD_SPEAK_ERROR: 'Проблемы при воспроизведении',
            MESSAGE_WORD_UPDATED: 'Слово успешно отредактированно',
            MESSAGE_WORDS_DELETED: 'Выделенные словавы были удалены',
            MESSAGE_WORDS_MOVED: 'Выбранные слова успешно перемещены в новую группу',
            MESSAGE_GROUP_DELETED: 'Группа успешно удалена',
            MESSAGE_GROUP_UPDATED: 'Группа успешно отредактирована',
            MESSAGE_GROUP_ADDED: 'Группа успешно добавлена',

            TITLE: 'Hello'
        });
        $translateProvider.translations('de', {

        });
        $translateProvider.preferredLanguage('ru');

    }]);
});


