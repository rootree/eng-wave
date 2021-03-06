/**
 * Defines the main routes in the application.
 * The routes you see here will be anchors '#/' unless specifically configured otherwise.
 */

define(['./app'], function (app) {
    'use strict';
    return app.config(['$translateProvider', function ($translateProvider) {

        $translateProvider.translations('ru', {

            // Common
            RESPONSE_CODE_1000: 'Переданы не корректные параметры',
            RESPONSE_CODE_1001: 'Произошла логическая ошибка, мы проинформированы о ней',
            RESPONSE_CODE_1002: 'Был получен пустой запрос',
            RESPONSE_CODE_1003: 'Произошла внутренняя ошибка',
            RESPONSE_CODE_1004: 'Запрошенный ресурс не найден',
            RESPONSE_CODE_1005: 'Требуется повторная авторизация',
            RESPONSE_CODE_0: 'Пожалуйста, пройдите авторизацию заново',

            // Word
            RESPONSE_CODE_7000: 'Запрошенное слово не найдено',
            RESPONSE_CODE_7001: 'Произносимое слово находится в процессе подготовки',

            // Strategy
            RESPONSE_CODE_6000: 'Запрошенная стратегия не найдена',
            RESPONSE_CODE_6001: 'Стратегия не содержит элементы',
            RESPONSE_CODE_6002: 'Не поддерживаемый элемент для добавления',
            RESPONSE_CODE_6003: 'Неверные настройки',

            // Package
            RESPONSE_CODE_5000: 'Запрошенный пакет слов не найден',
            RESPONSE_CODE_5001: 'Пакет уже был установлен',

            // Download
            RESPONSE_CODE_3000: 'Запрошенная загрузка не найдена',

            // Group
            RESPONSE_CODE_4000: 'Группа не найдена',
            RESPONSE_CODE_4001: 'Выбранная группа последняя, и не может быть удалена',
            RESPONSE_CODE_4002: 'Выбранная группа содержит слова и не может быть удалена',

            // Auth
            RESPONSE_CODE_2000: 'Пользователь с указанным адресом уже зарегистрирован',
            RESPONSE_CODE_2001: 'Авторизация провалена',

            BUTTON_SEND_MESSAGE: 'Отправить сообщение',
            BUTTON_SEND_AGAIN: 'Написать еще',
            BUTTON_REGISTRATION: 'Зарегистрироваться',
            BUTTON_CANCEL: 'Отмена',
            BUTTON_DELETE: 'Удалить',
            BUTTON_MOVE: 'Переместить',
            BUTTON_CHANGE: 'Изменить',
            BUTTON_EDIT: 'Редактировать',
            BUTTON_ADD: 'Добавить',
            BUTTON_ADD_WORD: 'Добавить',
            BUTTON_ADD_NEW_WORD: 'Добавить новое слово',
            BUTTON_ADD_GROUP: 'Добавить группу',
            BUTTON_ADD_WORD_AND_CLOSE: 'И закрыть',
            BUTTON_SAVE: 'Сохранить',
            BUTTON_OK: 'Ok',
            BUTTON_MOVE_TO_GROUP: 'Переместить',
            BUTTON_RESET_FILTER: 'Сбросить фильтр',
            BUTTON_ADD_STRATEGY: 'Добавить стратегию',
            BUTTON_DOWNLOAD: 'Скачать',
            BUTTON_QUIT: 'Выйти',
            BUTTON_RESTORE: 'Восстановить пароль',
            BUTTON_LOGIN: 'Войти',
            BUTTON_APPLY: 'Применить изменения',
            BUTTON_DEMO: 'Демо',

            LABEL_YOUR_MESSAGE: 'Ваше сообщение:',
            LABEL_YOUR_EMAIL: 'Email адрес:',
            LABEL_YOUR_NAME: 'Ваше имя:',
            LABEL_PASSWORD: 'Пароль:',
            LABEL_PASSWORD_REPEAT: 'Пароль повторно:',
            LABEL_NEW_STRATEGY_NAME: 'Название новой стратегии',
            LABEL_STRATEGY_NAME: 'Название стратегии',
            LABEL_NAME: 'Название:',
            LABEL_GROUP: 'Группа:',
            LABEL_STRATEGY: 'Стратегия:',
            LABEL_ORIGINAL: 'Оригинал:',
            LABEL_TRANSLATION: 'Перевод:',
            LABEL_EXAMPLE: 'Пример:',
            LABEL_ORIGIN_LANG: 'Язык оригинала:',
            LABEL_TRANSLATION_LANG: 'Язык перевода:',
            LABEL_NEW_PASSWORD: 'Новый пароль:',
            LABEL_NEW_PASSWORD_AGAIN: 'Пароль повторно:',
            LABEL_NOTIFICATION: 'Уведомления:',

            HEADER_FEEDBACK_FORM: 'Форма обратной связи',
            HEADER_REGISTRATION_FORM: 'Форма регистрации',
            HEADER_FEEDBACK: 'Обратная связь',
            HEADER_REGISTRATION: 'Регистрация пользователя',
            HEADER_FEEDBACK_SENT: 'Запрос отправлен',
            HEADER_TUTORIAL: 'Подсказка',

            TEXT_MAIN_MENU: 'Главное меню',
            TEXT_ABOUT_PROJECT: 'Немного о проекте...',
            TEXT_NOTHING_FOUND_DESC: 'При текущем фильтре ни одно слово найдено не было.',
            TEXT_NOTHING_FOUND: 'Ничего не найдено',
            TEXT_FOUND_WORDS: 'Найдено слов',
            TEXT_ALL_WORDS: 'Всего слов',
            TEXT_SELECT_GROUP: 'Выберите группу для переноса',
            TEXT_PSC: 'шт.',
            TEXT_SELECTED_ACTION: 'Действия над словами',
            TEXT_EDITING_WORD: 'Редактирование слова',
            TEXT_ADDING_WORD: 'Добавление слова',
            TEXT_EDITING_GROUP: 'Редактирование группы',
            TEXT_WORD_LIMITATION: 'Минимальная длинна три символа.',
            TEXT_FEEDBACK: 'Если у вас есть вопросы, пожелания или другая информация для обсуждения, вы можете написать нам, и мы свяжемся с вами в ближайшее время.',
            TEXT_FEEDBACK_SENT: 'Ваш запрос был успешно отправлен. Вы можете отправить новый запрос при желании.',
            TEXT_REGISTRATION_HEADER: 'Как только вы пройдете регистрацию, вы будете иметь полный доступ с сервису.',
            TEXT_STRATEGY_FOR_TRANSLATION: 'Стратегия для заучивания перевода оригинала',
            TEXT_STRATEGY_FOR_ORIGINAL: 'Стратегия для заучивания оригинала',
            TEXT_INITIAL_GROUP: 'Начальная группа',
            TEXT_EMPTY_STRATEGY: 'Данная стратегия не содержит элементов. Вы не сможете использовать её для скачивания файлов.',
            TEXT_INSTALL: 'Установить',
            TEXT_INSTALLED: 'Установлен',
            TEXT_LEVEL_BEGINNER: 'Новичок',
            TEXT_LEVEL_ADVANCED: 'Продвинуты',
            TEXT_WORD_COUNT: 'Количество слов',
            TEXT_LEVEL: 'Уровень',
            TEXT_ELEMENTS: 'Элементы для добавления',
            TEXT_ELEMENTS_ADDED: 'Добавленные элементы',
            TEXT_TRANSLATION: 'Перевод',
            TEXT_TRANSLATION_EXAMPLE: 'Пример перевода',
            TEXT_SILENT: 'Тишина',
            TEXT_ORIGINAL: 'Оригинал',
            TEXT_ORIGINAL_EXAMPLE: 'Пример оригинала',
            TEXT_ADD_ELEMENT: 'Добавить: ',
            TEXT_ADD_ELEMENTS: 'Добавьте элементы для воспроизведения.',
            TEXT_ELEMENT_SETTINGS: 'Настройка: ',
            TEXT_ORIGINAL_TIME: 'Время оригинала',
            TEXT_ORIGINAL_EXAMPLE_TIME: 'Время примера оригинала',
            TEXT_TRANSLATION_TIME: 'Время перевода',
            TEXT_TRANSLATION_EXAMPLE_TIME: 'Время примера перевода',
            TEXT_SILENT_TIME: 'Тишина на определённое время',
            TEXT_TIME_IN_SECONDS: 'Время в секундах:',
            TEXT_EMPTY_STRATEGY_WARNING: 'Данная стратегия не содержит элементов. Вы не сможете использовать её для скачивания файлов.',
            TEXT_SILENT_DURING_ORIG_TIME: 'на время оригинала',
            TEXT_SILENT_DURING_ORIG_EXAM_TIME: 'на время примера оригинала',
            TEXT_SILENT_DURING_TRANS_TIME: 'на время перевода',
            TEXT_SILENT_DURING_TRANS_EXAM_TIME: 'на время примера перевода',
            TEXT_SILENT_IN_SEC: 'сек.',
            TEXT_ADDING_GROUP: 'Добавление группы',
            TEXT_TUTORIAL: 'Туториал',
            TEXT_FEED_BACK_SUPPORT: 'Предложения / Поддержка',
            TEXT_THANK_YOU: 'Спасибо',
            TEXT_STRATEGIES_COUNT: 'Кол-во стратегий',

            TEXT_DOWNLOAD_PAGE_DESC: 'Вы можете загрузить на ваш компьютер ваши слова, в виде отдельных файлов, которые будут собраны в такой последовательности как стратегии.',
            TEXT_DOWNLOAD_EMPTY_STRATEGY: 'Ни одной стратегии',
            TEXT_DOWNLOAD_EMPTY_STRATEGY_DESC: 'У вас нет ни одной стратегии. Не понятно в какой последовательности воспроизводить фразы.',
            TEXT_DOWNLOAD_EMPTY: 'Пусто',
            TEXT_DOWNLOAD_EMPTY_DESC: 'Ни одной из стратегий для слов для перевода не найдено. Добавьте хотя бы одну, иначе вы не сможете загрузить для прослушивания ваши слова.',
            TEXT_DOWNLOAD_WORDS_GROUP: 'Группа слов',
            TEXT_DOWNLOAD_STRATEGY: 'Стратегия',
            TEXT_DOWNLOAD_CREATED_DATE: 'Дата создания',
            TEXT_DOWNLOAD_STATUS: 'Статус',
            TEXT_DOWNLOAD_STATUS_QUEUE: 'В очереди',
            TEXT_DOWNLOAD_STATUS_PREPARING: 'Подготавливается',
            TEXT_DOWNLOAD_STATUS_READY: 'Готово',
            TEXT_DOWNLOAD_STATUS_CANCELED: 'Отменен',
            TEXT_DOWNLOAD_SIZE: 'Размер',
            TEXT_DOWNLOAD_TOTAL: 'Всего',
            TEXT_DOWNLOAD_FILE_PRE: 'Подготовка файла',

            TEXT_FORGOT_AUTH: 'У вас уже есть личный кабинет, для регистрации надо выйти из текущего кабинета.',
            TEXT_FORGOT_EMAIL_DESC: 'Будет выслана письмо восстановления.',

            TEXT_LOGIN_AUTH: 'Авторизация',
            TEXT_LOGIN_FORGOT_PASSWORD: 'Забыли пароль?',
            TEXT_LOGIN_FEEDBACK: 'Написать нам',

            TEXT_SETTINGS_PERSONAL_INFO: 'Персональная информация',
            TEXT_SETTINGS_SECURITY: 'Безопасность',
            TEXT_SETTINGS_EXPIRE_DAYS_FOR_PASS: 'Истечение срока действия пароля',
            TEXT_SETTINGS_FILES_READY: 'Файлы для загрузки подготовлены',

            TEXT_PACKAGES_PAGE_DESC: 'Пакеты слов для закачки.',

            TEXT_STRATEGIES_PAGE_DESC: 'Стратегии определят последовательность воспроизведения оригинальной фразы, её перевода и тишины. На время тишины вы можете проговаривать фразу для лучшего запоминания.',
            TEXT_STRATEGIES_EMPTY: 'Пусто',
            TEXT_STRATEGIES_EMPTY_DESC: 'Ни одной из стратегий для оригинальных слов не найдено. Добавьте хотя бы одну, иначе вы не сможете загрузить для прослушивания ваши слова.',

            TEXT_WORDS_TOTAL_GROUP: 'Всего групп',
            TEXT_WORDS_TOTAL_WORDS_GROUP: 'Всего слов в группе',
            TEXT_WORDS_EMPTY_GROUP: 'Пустая группа',
            TEXT_WORDS_EMPTY_GROUP_DESC: 'Текущая группа не содержит слов. Вы можете наполнить её.',

            MESSAGE_DOWNLOAD_QUEUE: 'Новая загрузка поставлена в очередь для наполнения',
            MESSAGE_DOWNLOAD_DELETED: 'Загрузка удалена',
            MESSAGE_AUTH_COMPLETED: 'Авторизация уже была пройдена',
            MESSAGE_PACKAGE_INSTALLED: 'Пакет был успешно установлен',
            MESSAGE_REG_PASSWORD_MISMATCH: 'Проверьте пароль или его повторение.',
            MESSAGE_REG_COMPLETED: 'Регистрация произведена.',
            MESSAGE_SETTING_SAVED: 'Изменения зафиксированы.',
            MESSAGE_STRATEGY_DELETED: 'Выбранная стратегия удалена',
            MESSAGE_STRATEGY_SAVED: 'Стратегия обновлена',
            MESSAGE_SUPPORT_SENT: 'Ваше сообщение отправлено.',
            MESSAGE_WORD_AUTH: 'Пройдите авторизацию',
            MESSAGE_WORD_DELETED: 'Слово удалено',
            MESSAGE_WORD_ADDED: 'Новое слово успешно добавлено',
            MESSAGE_WORD_SPEAK_ERROR: 'Проблемы при воспроизведении',
            MESSAGE_WORD_UPDATED: 'Слово успешно отредактировано',
            MESSAGE_WORDS_DELETED: 'Выделенные слова были удалены',
            MESSAGE_WORDS_MOVED: 'Выбранные слова успешно перемещены в новую группу',
            MESSAGE_GROUP_DELETED: 'Группа успешно удалена',
            MESSAGE_GROUP_UPDATED: 'Группа успешно отредактирована',
            MESSAGE_GROUP_ADDED: 'Группа успешно добавлена',
            MESSAGE_ERROR_ON_SOUND: 'Невозможно проиграть звук',
            MESSAGE_REQUIRED_AUTH: 'Для продолжения авторизуйтесь',
            MESSAGE_LOGOUT_WAS_BEFORE: 'Выход из аккаунта уже сделан',
            MESSAGE_LOGOUT_COMPLETE: 'Выход из аккаунта произведён',
            MESSAGE_DEVELOPING: 'Данная функция в процессе разработки',
            MESSAGE_NEW_STRATEGY_ADDED: 'Новая стратегия добавлена',

            PAGINATION_FINISH: 'Конец',
            PAGINATION_START: 'Начало',

            TUTORIAL_DOWNLOAD_1: 'Загрузка - это последний шаг для получения mp3-файлов.',
            TUTORIAL_DOWNLOAD_2: 'Как только вы определитесь какие слова вы хотите выучить и создали стратегию, вы можете создать загрузку. Загрузка создается по группам слов.',
            TUTORIAL_DOWNLOAD_3: 'Создание mp3-файлов требует время. Поэтому новая загрузка поставится в очередь на подготовку.',
            TUTORIAL_DOWNLOAD_4: 'Как только ваша загрузка будет готова, мы пришлем на ваш электронный адрес, ссылку для скачивания, это будет ZIP-архив, с mp3-файлами. Так же готовый файл можно будет скачать на этой странице.',
            TUTORIAL_DOWNLOAD_5: 'Что делать с загрузкой',
            TUTORIAL_DOWNLOAD_6: 'После скачивания архива, вам его надо распаковать, полученные mp3-файлы открыть в любом mp3-плеере.',
            TUTORIAL_DOWNLOAD_7: 'В самом плеере можно включить перемешивание треков, чтобы слова шли не по алфавиту. Т.к. при повторном прослушивании по алфавиту, вы будете слушать слова в алфавитном порядке, и слова в конце алфавита, могут остаться не прослушанными.',

            TUTORIAL_WORDS_1: 'В двух словах',
            TUTORIAL_WORDS_2: 'Данные сервис - это еще один способ улучшить уровень вашего иностранного языка.',
            TUTORIAL_WORDS_3: 'На выходе, вы получите заведённые вами слова, в виде mp3-файлов. Каждый mp3-файл будет содержать произношение ваших слов, в той последовательности, которую вы можете определить сами. Полученные mp3-файлы можно слушать в комфортном для вас месте.',
            TUTORIAL_WORDS_4: 'В Интернете можно встретить много подготовленных наборов слов, записанные диктором, в данном сервисе, вы можете сами создавать такие наборы, в добавок самому определять в какой последовательности диктору следует произносить ваши слова.',
            TUTORIAL_WORDS_5: 'Слова',
            TUTORIAL_WORDS_6: 'На данной страницы, вы можете собирать слова, которые вы бы хотели запомнить лучше или выучить.',
            TUTORIAL_WORDS_7: 'К примеру, вы ходите на курсы иностранного языка, на них вы встречаете новые иностранные слова, и чтобы их лучше запомнить, их можно внести на этой странице, скачать произношение, затем, к примеру, по дороге домой, вы можете непринужденно прослушать и повторять новые слова.',
            TUTORIAL_WORDS_8: 'Таким же образом можно заводить любые слова, которые вы встретили и хотели бы выучить.',
            TUTORIAL_WORDS_9: 'Слова - это не предел, вы можете вносить хоть целые предложения.',
            TUTORIAL_WORDS_10: 'Группы',
            TUTORIAL_WORDS_11: 'Для большей организации слов, вы можете разбивать их по группам.',

            TUTORIAL_STRATEGIES_1: 'Стратегии',
            TUTORIAL_STRATEGIES_2: 'Стратегии - так мы назвали последовательность, по которой наш диктор будет надиктовывать оригинал слова и его перевод при подготовке mp3-файла.',
            TUTORIAL_STRATEGIES_3: 'Мы создали три типа повторения:',
            TUTORIAL_STRATEGIES_4: 'Как это работает',
            TUTORIAL_STRATEGIES_5: 'Если создать стратегию, для заучиваемого слова "Hello", с переводом "Здравствуйте", с такой последовательностью:',
            TUTORIAL_STRATEGIES_6: 'Тогда в mp3-файле будет записано:',
            TUTORIAL_STRATEGIES_7: 'Здравствуйте - Hello - Здравствуйте - Hello',
            TUTORIAL_STRATEGIES_8: 'Тишина полезна, если вы:',
            TUTORIAL_STRATEGIES_9: 'Хотите проверить себя.',
            TUTORIAL_STRATEGIES_10: 'К примеру, диктор произносит оригинал, на время паузы, вы пытаетесь вспомнить как переводится оригинальное слово, после паузы, диктор произносит перевод.',
            TUTORIAL_STRATEGIES_11: 'Или совсем наоборот, диктор произносит перевод слова, на время паузы вы пытаетесь вспомнить как произнесённое слово будет на иностранно языке, после паузы диктор произнесёт правильный ответ.',
            TUTORIAL_STRATEGIES_12: 'Хотите научится произносить слова.',
            TUTORIAL_STRATEGIES_13: 'Вы можете создать стратегию, где после оригинала, есть пауза, которую можно использовать для повторения оригинального слова. Повторения оригинала, паузы и перевода можно вставлять в стратегию сколько угодно раз.',
            TUTORIAL_STRATEGIES_14: 'Создавая стратегию, вы можете определить, последовательность Оригинала, Тишины и Перевода.',
            TUTORIAL_STRATEGIES_15: 'К примеру вы можете создать стратегию, где вообще нет Тишины и добавить в нее только последовательность Оригинала и Перевода.',

            TITLE_AUTH: 'Авторизация',
            TITLE_SUGGESTION: 'Предложить совет',
            TITLE_SUPPORT: 'Поддержка',
            TITLE_ABOUT: 'О проекте',
            TITLE_REGISTRATION: 'Регистрация',
            TITLE_PASSWORD_RECOVERY: 'Восстановление пароля',
            TITLE_STRATEGIES: 'Стратегии',
            TITLE_DOWNLOADS: 'Загрузки',
            TITLE_SETTINGS: 'Настройки',
            TITLE_PACKS: 'Пакеты',
            TITLE_WORDS: 'Слова'
        });
        $translateProvider.translations('en', {
            // Common
            RESPONSE_CODE_1000: 'The parameters are not correct',
            RESPONSE_CODE_1001: 'An error has occurred, we have been informed about it',
            RESPONSE_CODE_1002: 'Empty request',
            RESPONSE_CODE_1003: 'An internal error has occurred',
            RESPONSE_CODE_1004: 'The requested resource has not been found',
            RESPONSE_CODE_1005: 'You must log in again',
            RESPONSE_CODE_0: 'Please log in again',

            // Word
            RESPONSE_CODE_7000: 'The requested word has not been found',
            RESPONSE_CODE_7001: 'This requested word’s pronunciation is still being processed',

            // Strategy
            RESPONSE_CODE_6000: 'The requested strategy has not been found',
            RESPONSE_CODE_6001: 'The strategy contains no elements',
            RESPONSE_CODE_6002: 'Unsupported element',
            RESPONSE_CODE_6003: 'Invalid configuration',

            // Package
            RESPONSE_CODE_5000: 'The requested words package has not been found',
            RESPONSE_CODE_5001: 'The package has already been installed',

            // Download
            RESPONSE_CODE_3000: 'The requested download is not found',

            // Group
            RESPONSE_CODE_4000: 'The group has not been found',
            RESPONSE_CODE_4001: 'The selected group is the only remaining and therefore can not be deleted',
            RESPONSE_CODE_4002: 'The selected group contains words and therefore can not be deleted',

            // Auth
            RESPONSE_CODE_2000: 'The specified address has already been registered',
            RESPONSE_CODE_2001: 'Authorization failed',

            BUTTON_SEND_MESSAGE: 'Send a message',
            BUTTON_SEND_AGAIN: 'Submit another request',
            BUTTON_REGISTRATION: 'Sign up',
            BUTTON_CANCEL: 'Cancel',
            BUTTON_DELETE: 'Delete',
            BUTTON_MOVE: 'Move',
            BUTTON_CHANGE: 'Change',
            BUTTON_EDIT: 'Edit',
            BUTTON_ADD: 'Add',
            BUTTON_ADD_WORD: 'Add',
            BUTTON_ADD_NEW_WORD: 'Add a new word',
            BUTTON_ADD_GROUP: 'Add a group',
            BUTTON_ADD_WORD_AND_CLOSE: 'and Close',
            BUTTON_SAVE: 'Save',
            BUTTON_OK: 'Ok',
            BUTTON_MOVE_TO_GROUP: 'Move',
            BUTTON_RESET_FILTER: 'Reset Filter',
            BUTTON_ADD_STRATEGY: 'Add a strategy',
            BUTTON_DOWNLOAD: 'Download',
            BUTTON_QUIT: 'Exit',
            BUTTON_RESTORE: 'Recover password',
            BUTTON_LOGIN: 'Login',
            BUTTON_APPLY: 'Apply changes',
            BUTTON_DEMO: 'Demo',

            LABEL_YOUR_MESSAGE: 'Your message',
            LABEL_YOUR_EMAIL: 'Email address:',
            LABEL_YOUR_NAME: 'Your name:',
            LABEL_PASSWORD: 'Password:',
            LABEL_PASSWORD_REPEAT: 'Password again:',
            LABEL_NEW_STRATEGY_NAME: 'The name of the new strategy',
            LABEL_STRATEGY_NAME: 'Name',
            LABEL_NAME: 'Title:',
            LABEL_GROUP: 'Group:',
            LABEL_STRATEGY: 'Strategy:',
            LABEL_ORIGINAL: 'Original:',
            LABEL_TRANSLATION: 'Translation:',
            LABEL_EXAMPLE: 'Example:',
            LABEL_ORIGIN_LANG: 'Original language:',
            LABEL_TRANSLATION_LANG: 'Translation language:',
            LABEL_NEW_PASSWORD: 'New Password:',
            LABEL_NEW_PASSWORD_AGAIN: 'Password again:',
            LABEL_NOTIFICATION: 'Notification:',

            HEADER_FEEDBACK_FORM: 'Feedback form',
            HEADER_REGISTRATION_FORM: 'Registration Form',
            HEADER_FEEDBACK: 'Contact Us',
            HEADER_REGISTRATION: 'User Registration',
            HEADER_FEEDBACK_SENT: 'Request sent',
            HEADER_TUTORIAL: 'Tip',

            TEXT_MAIN_MENU: 'Main menu',
            TEXT_ABOUT_PROJECT: 'A little bit about the project ...',
            TEXT_NOTHING_FOUND_DESC: 'No words have been found matching the current filter.',
            TEXT_NOTHING_FOUND: 'Nothing found',
            TEXT_FOUND_WORDS: 'Found words',
            TEXT_ALL_WORDS: 'Total words',
            TEXT_SELECT_GROUP: 'Select a group to move to',
            TEXT_PSC: 'pcs.',
            TEXT_SELECTED_ACTION: 'Actions on the words',
            TEXT_EDITING_WORD: 'Edit a word',
            TEXT_ADDING_WORD: 'Add a word',
            TEXT_EDITING_GROUP: 'Edit group',
            TEXT_WORD_LIMITATION: 'The minimum length of three characters.',
            TEXT_FEEDBACK: 'If you have any questions, suggestions or other information to discuss, you can write us and we will contact you shortly.',
            TEXT_FEEDBACK_SENT: 'Your request has been submitted successfully. You can submit a new request, if desired.',
            TEXT_REGISTRATION_HEADER: 'Once you have registered, you will have full access to the service.',
            TEXT_STRATEGY_FOR_TRANSLATION: 'Strategy for learning the translation',
            TEXT_STRATEGY_FOR_ORIGINAL: 'Strategy for learning the original',
            TEXT_INITIAL_GROUP: 'Default group',
            TEXT_EMPTY_STRATEGY: 'This strategy does not contain any elements. You can not use it to create files for download.',
            TEXT_INSTALL: 'Install',
            TEXT_INSTALLED: 'Installed',
            TEXT_LEVEL_BEGINNER: 'Beginner',
            TEXT_LEVEL_ADVANCED: 'Advanced',
            TEXT_WORD_COUNT: 'Number of words',
            TEXT_LEVEL: 'Level',
            TEXT_ELEMENTS: 'Elements to add',
            TEXT_ELEMENTS_ADDED: 'Added elements',
            TEXT_TRANSLATION: 'Translation',
            TEXT_TRANSLATION_EXAMPLE: 'An example of the translation',
            TEXT_SILENT: 'Pause',
            TEXT_ORIGINAL: 'Original',
            TEXT_ORIGINAL_EXAMPLE: 'An example of the original',
            TEXT_ADD_ELEMENT: 'Add:',
            TEXT_ADD_ELEMENTS: 'Add examples to play.',
            TEXT_ELEMENT_SETTINGS: 'Configuring:',
            TEXT_ORIGINAL_TIME: 'Time of an original',
            TEXT_ORIGINAL_EXAMPLE_TIME: 'Time of an original\'s example',
            TEXT_TRANSLATION_TIME: 'Time of a translation',
            TEXT_TRANSLATION_EXAMPLE_TIME: 'Time of a translation\'s example',
            TEXT_SILENT_TIME: 'The pause at a certain time',
            TEXT_TIME_IN_SECONDS: 'Time in seconds:',
            TEXT_EMPTY_STRATEGY_WARNING: 'This strategy contains no elements. You will not be able to use it for downloading files.',
            TEXT_SILENT_DURING_ORIG_TIME: 'at the time of the original',
            TEXT_SILENT_DURING_ORIG_EXAM_TIME: 'at the time of the original\'s example',
            TEXT_SILENT_DURING_TRANS_TIME: 'at the time of the translation',
            TEXT_SILENT_DURING_TRANS_EXAM_TIME: 'at the time of the translation\'s example',
            TEXT_SILENT_IN_SEC: 'seconds',
            TEXT_ADDING_GROUP: 'Add group',
            TEXT_TUTORIAL: 'Tutorial',
            TEXT_FEED_BACK_SUPPORT: 'Suggestions / Support',
            TEXT_THANK_YOU: 'Thank You',
            TEXT_STRATEGIES_COUNT: 'Number of strategies',

            TEXT_DOWNLOAD_PAGE_DESC: 'You may download an archive with the compiled strategy, which consist of the words’ pronunciation, pauses, etc. ',
            TEXT_DOWNLOAD_EMPTY_STRATEGY: 'There are no strategies defined.',
            TEXT_DOWNLOAD_EMPTY_STRATEGY_DESC: 'You will be able to create the downloads after you create at least one strategy.',
            TEXT_DOWNLOAD_EMPTY: 'There are not downloads',
            TEXT_DOWNLOAD_EMPTY_DESC: 'No downloads have been found. You can create them in anytime you want.',
            TEXT_DOWNLOAD_WORDS_GROUP: 'Group of words',
            TEXT_DOWNLOAD_STRATEGY: 'Strategy',
            TEXT_DOWNLOAD_CREATED_DATE: 'Created',
            TEXT_DOWNLOAD_STATUS: 'Status',
            TEXT_DOWNLOAD_STATUS_QUEUE: 'In line',
            TEXT_DOWNLOAD_STATUS_PREPARING: 'Being prepared',
            TEXT_DOWNLOAD_STATUS_READY: 'Finish',
            TEXT_DOWNLOAD_STATUS_CANCELED: 'Canceled',
            TEXT_DOWNLOAD_SIZE: 'Size',
            TEXT_DOWNLOAD_TOTAL: 'Total',
            TEXT_DOWNLOAD_FILE_PRE: 'Creating a download',

            TEXT_FORGOT_AUTH: 'You are already logged in.',
            TEXT_FORGOT_EMAIL_DESC: 'An email will be sent with instructions on how to recover your password.',

            TEXT_LOGIN_AUTH: 'Authorization',
            TEXT_LOGIN_FORGOT_PASSWORD: 'Forgot password?',
            TEXT_LOGIN_FEEDBACK: 'Contact us',

            TEXT_SETTINGS_PERSONAL_INFO: 'Personal information',
            TEXT_SETTINGS_SECURITY: 'Security',
            TEXT_SETTINGS_EXPIRE_DAYS_FOR_PASS: 'Your password is expired',
            TEXT_SETTINGS_FILES_READY: 'Downloads are ready',

            TEXT_PACKAGES_PAGE_DESC: 'Words packages available.',

            TEXT_STRATEGIES_PAGE_DESC: 'A strategy defines the playback sequence of the original phrase, its translation and/or pause. You can use the pause to try to remember the translation and/or speak it out loud.',
            TEXT_STRATEGIES_EMPTY: 'Empty',
            TEXT_STRATEGIES_EMPTY_DESC: 'None of the strategies could be found. You must add at least one strategy in order  to download your words.',

            TEXT_WORDS_TOTAL_GROUP: 'Total groups',
            TEXT_WORDS_TOTAL_WORDS_GROUP: 'Total words in the group',
            TEXT_WORDS_EMPTY_GROUP: 'Empty group',
            TEXT_WORDS_EMPTY_GROUP_DESC: 'The current group has no words. You should add words first.',


            MESSAGE_DOWNLOAD_QUEUE: 'A new download has been added to the queue.',
            MESSAGE_DOWNLOAD_DELETED: 'Download deleted',
            MESSAGE_AUTH_COMPLETED: 'You logged in successfully',
            MESSAGE_PACKAGE_INSTALLED: 'The package has been successfully installed',
            MESSAGE_REG_PASSWORD_MISMATCH: 'Passwords do not match.',
            MESSAGE_REG_COMPLETED: 'You singed up successfully.',
            MESSAGE_SETTING_SAVED: 'Settings have been updated.',
            MESSAGE_STRATEGY_DELETED: 'The strategy has been deleted',
            MESSAGE_STRATEGY_SAVED: 'The strategy has been updated',
            MESSAGE_SUPPORT_SENT: 'Your message has been sent.',
            MESSAGE_WORD_AUTH: 'Please sign in',
            MESSAGE_WORD_DELETED: 'Word has been removed',
            MESSAGE_WORD_ADDED: 'A new word has been successfully added',
            MESSAGE_WORD_SPEAK_ERROR: 'Playback problems',
            MESSAGE_WORD_UPDATED: 'The word has been successfully updated',
            MESSAGE_WORDS_DELETED: 'The selected words have been removed',
            MESSAGE_WORDS_MOVED: 'The selected words have benn successfully moved to another group',
            MESSAGE_GROUP_DELETED: 'The group has been successfully deleted',
            MESSAGE_GROUP_UPDATED: 'The group has been successfully updated',
            MESSAGE_GROUP_ADDED: 'The group has been successfully added',
            MESSAGE_ERROR_ON_SOUND: 'You can not play this sound',
            MESSAGE_REQUIRED_AUTH: 'Sign in to continue',
            MESSAGE_LOGOUT_WAS_BEFORE: 'You have already logged out',
            MESSAGE_LOGOUT_COMPLETE: 'You have successfully logged out',
            MESSAGE_DEVELOPING: 'This function is under construction',
            MESSAGE_NEW_STRATEGY_ADDED: 'The new strategy has been added',

            PAGINATION_FINISH: 'End',
            PAGINATION_START: 'Start',

            TUTORIAL_DOWNLOAD_1: 'Download is the last step to obtain mp3-files.',
            TUTORIAL_DOWNLOAD_2: 'As soon as you decide which words do you want to learn and create a strategy, you can create a download. A download is based on groups of words.',
            TUTORIAL_DOWNLOAD_3: 'Generating of mp3-files takes time. A new download will be put in the queue for creating.',
            TUTORIAL_DOWNLOAD_4: 'As soon as your download is ready, we will send you a download link. You should follow this link to get a ZIP archive containing mp3-files. The file can also be downloaded on this page.',
            TUTORIAL_DOWNLOAD_5: 'How to use the ZIP archive',
            TUTORIAL_DOWNLOAD_6: 'After downloading the archive you will need to unpack the mp3-files and open them in any mp3-player.',
            TUTORIAL_DOWNLOAD_7: 'Tip. You probably want to turn on shuffe function in your favorite player. When you listen without shuffling, the words are being played in alphabetical order, so there is a less change you’ll even listen to words from the end of the list.',


            TUTORIAL_WORDS_1: 'In a nutshell',
            TUTORIAL_WORDS_2: 'This service is yet another way to improve your skills in a foreign language.',
            TUTORIAL_WORDS_3: 'As the end result you will get audio files with the pronunciation of the words in the sequence that you define by yourself.',
            TUTORIAL_WORDS_4: 'You can find a lot of words sets recorded by different announcers, Using this service you can create your own sets, and you can also decide in which order should the announcer pronounce your words.',
            TUTORIAL_WORDS_5: 'Words',
            TUTORIAL_WORDS_6: 'On this page you can keep words that you think require the most attention.',
            TUTORIAL_WORDS_7: 'For instance you hear a new word while attending a foreign language course. If you want to remember the new word better, you can add it using this page, download the pronunciation and then listen to and repeat on the way home.',
            TUTORIAL_WORDS_8: '',
            TUTORIAL_WORDS_9: 'You are not limited in adding single words, you can add phrases or even whole sentences.',
            TUTORIAL_WORDS_10: 'Groups',
            TUTORIAL_WORDS_11: 'For a smarter way to organize your words, you can divide them into groups.',

            TUTORIAL_STRATEGIES_1: 'Strategy',
            TUTORIAL_STRATEGIES_2: 'Strategy is a sequence in which our announcer will pronounce the original word and its translation in the compiled mp3-file.',
            TUTORIAL_STRATEGIES_3: 'We have created three types of available elements:',
            TUTORIAL_STRATEGIES_4: 'How it works',
            TUTORIAL_STRATEGIES_5: 'You can create a strategy to memorize the word "Ciao" in Italian, with the translation of "Hello", with the following sequence:',
            TUTORIAL_STRATEGIES_6: 'Then the mp3-file will be written to:',
            TUTORIAL_STRATEGIES_7: 'Hello - Ciao - Hello - Ciao',
            TUTORIAL_STRATEGIES_8: 'Pause is useful if you:',
            TUTORIAL_STRATEGIES_9: 'Want to check yourself.',
            TUTORIAL_STRATEGIES_10: 'For example, the announcer pronounces the original, and during the pause you try to remember how to translate the original word, then after the pause, the announcer pronounces the translation.',
            TUTORIAL_STRATEGIES_11: 'Or on the contrary, the announcer pronounces the translation of the word, then for the duration of the pause you try to recall the translation of the word into another language, and then after a pause, the announcer pronounces the corrent answer.',
            TUTORIAL_STRATEGIES_12: 'Do you want to learn how to pronounce certain words?',
            TUTORIAL_STRATEGIES_13: 'You can create a strategy in which after the original, there is a pause that can be used to repeat the original word. Such repetition of the original word, a pause, and the translation can be inserted into the strategy multiple times.',
            TUTORIAL_STRATEGIES_14: 'By creating a strategy, you can determine the sequence of the original, a pause and the translation.',
            TUTORIAL_STRATEGIES_15: 'For example, you can create a strategy in which there is no pause only adding to the strategy the sequence of Original and Translation.',

            TITLE_AUTH: 'Authorization',
            TITLE_SUGGESTION: 'Make a suggestion',
            TITLE_SUPPORT: 'Support',
            TITLE_ABOUT: 'About',
            TITLE_REGISTRATION: 'Registration',
            TITLE_PASSWORD_RECOVERY: 'Password recovery',
            TITLE_STRATEGIES: 'Strategies',
            TITLE_DOWNLOADS: 'Downloads',
            TITLE_SETTINGS: 'Settings',
            TITLE_PACKS: 'Packages',
            TITLE_WORDS: 'Words'

        });
        $translateProvider.preferredLanguage('en');
    }]);
});


