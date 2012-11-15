(function($) {
    $.jnotify = function(title, text, pictureSrc, options) {
        var stackContainer, messageWrap, messageBox, messageBody, messageTextBox, closeButton, messagePicture, image;
 
        options = $.extend({
            lifeTime: 0,
            click: undefined
        }, options);
 
        // находим контейнер с сообщениями, если его нет, тогда создаём
        stackContainer = $('#notifier-box');
        if (!stackContainer.length) {
            stackContainer = $('<div>', {id: 'notifier-box'}).prependTo(document.body);
        }
 
        // создаём элементы вертски контейнера сообщения
        messageWrap = $('<div>', {
            className: 'message-wrap',
            css: {
                display: 'none'
            }
        });
 
        messageBox = $('<div>', {
            className: 'message-box'
        });
 
        messageHeader = $('<div>', {
            className: 'message-header',
            text: title
        });
 
        messageBody = $('<div>', {
            className: 'message-body'
        });
 
        messageTextBox = $('<span>', {
            text: text
        });
 
        closeButton = $('<a>', {
            className: 'message-close',
            href: '#',
            title: 'Закрыть уведомление',
            click: function() {
                $(this).parent().parent().fadeOut(300, function() {
                    $(this).remove();
                });
            }
        });
 
        // если указан путь к картинке, тогда создадим контейнер и для неё :)
        if (pictureSrc != undefined) {
            messagePicture = $('<div>', {
                className: 'thumb'
            });
            image = $('<img>', {
                src: pictureSrc
            });
        }
 
        // теперь расположим все на свои места
        messageWrap.appendTo(stackContainer).fadeIn();
        messageBox.appendTo(messageWrap);
        closeButton.appendTo(messageBox);
        messageHeader.appendTo(messageBox);
        messageBody.appendTo(messageBox);
 
        if (messagePicture != undefined) {
            messagePicture.appendTo(messageBody);
            image.appendTo(messagePicture);
        }
        messageTextBox.appendTo(messageBody);
 
        // если время жизни уведомления больше 0, ставим таймер
        if (options.lifeTime > 0) {
            setTimeout(function() {
                $(messageWrap).fadeOut(300, function() {
                    $(this).remove();
                });
            }, options.lifeTime);
        }
 
        // если установлен колбек
        if (options.click != undefined) {
            messageWrap.click(function(e) {
                if (!jQuery(e.target).is('.message-close')) {
                    options.click.call(this);
                }
            });
        }
 
        return this;
    }
})(jQuery);