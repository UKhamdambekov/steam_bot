INSERT INTO lng(ru, uz, en, flag) 
    VALUES ("Успешно", "Muvaffaqiyatli", "Success", "success");

INSERT INTO lng(ru, uz, en, flag) 
    VALUES ("Ошибка", "Xatolik", "Error", "error");  

INSERT INTO lng(ru, uz, en, flag) 
    VALUES ("Введите Ф.И.О. ученика:", "O'quvchining F.I.Sh. ni kiriting:", "Enter your full name:", "setname");

INSERT INTO lng(ru, uz, en, flag) 
    VALUES ("Введите район школы:", "Maktab rayonini kiriting:", "Enter the district of your school:", "setdistrict");  

INSERT INTO lng(ru, uz, en, flag) 
    VALUES ("Введите номер школы:", "Maktab raqamini kiriting:", "Enter the school number:", "setschool");

INSERT INTO lng(ru, uz, en, flag) 
    VALUES ("Введите класс ученика:", "O'quvchi sinfini kiriting:", "Enter your grade:", "setclass");

INSERT INTO lng(ru, uz, en, flag) 
    VALUES ("Здравствуйте, +NAME+! \n Для подробной информации /help", 
        "Salom +NAME+! To'liq ma'lumot uchun /help ni bosing", 
        "Hi, +NAME+! For more information, click /help", "welcome");

INSERT INTO lng(ru, uz, en, flag) 
    VALUES ("Бот остановлен!", "Bot to'xtatilindi!", "Bot stopped!", "stop");

INSERT INTO lng(ru, uz, en, flag) 
    VALUES ("Вы можете отправить видео и фото.\nВыберите урок для отправки видео.\nУроки:", 
    "Foto va video yuklashingiz mumkun.\nBuning uchun fanni tanlang.\nFanlar:", 
    "You can send media in photo or video format.\nChoose the class for sending media.\nClasses:", "help");
    
INSERT INTO lng(ru, uz, en, flag) 
    VALUES ("Не понимаю команду!", "Buyruqni chunmadm!", "I don’t understand the command!", "batcommand");

INSERT INTO lng(ru, uz, en, flag) 
    VALUES ("Отправьте видео или фото для урока +TEACH+\nДля отмены нажмите /cancel", 
        "+TEACH+ fani uchun foto yoki videoni yuklang\nBekor qilish uchun /cancel ni bosing", 
        "Send a photo or video for the class of +TEACH+\nFor canceled information, click /cancel", "setclasses");
        
INSERT INTO lng(ru, uz, en, flag) 
    VALUES ("Сначала выберите урок!", "Avval fanni tanlang!", "I don’t understand the command!", "errclass");

INSERT INTO lng(ru, uz, en, flag) 
    VALUES (" Ошибка добавление в БД!", "Bazaga qoshishda xatolik!", "I don’t understand the command!", "errdb");

INSERT INTO lng(ru, uz, en, flag) 
    VALUES ("Неверный тип файла. Доступные расширение .mp4, .3gp, .mov, .avi, .wmv", 
    "Noto'g'ri format. Faqat .mp4, .3gp, .mov, .avi, .wmv formatlari.", 
    "I don’t understand the command!", "errformat");

INSERT INTO lng(ru, uz, en, flag) 
    VALUES ("Ваш аккаунт заблокирован администратором!", 
        "Sizni profilingiz adminstrator tomonidan bloklangan!", 
        "Your account has been blocked by the administrator!", "block");

INSERT INTO lng(ru, uz, en, flag) 
    VALUES ("Поздравляем! \n Вы победили в конкурсе по предмету +CLASSES+ за эту неделю. Продолжайте в этом же духе!", 
        "Tabriklaymiz! \n Siz +CLASSES+ fani bo'yicha bu hafta g'olib bo'ldingiz! Shunday davom eting!", 
        "Congratulations! \n You have won in the competition on the subject +CLASSES+ for this week. Keep your spirits up!", 
        "congurlation");

INSERT INTO lng(ru, uz, en, flag) 
    VALUES ("Теперь в системе доступен урок +CLASSES+! Для выбора введите команду +COMMAND+", 
        "Tizimga yangi +CLASSES+ fani qo'shildi! Tanlash uchun +COMMAND+ ni bosing.", 
        "Now the lesson +CLASSES+ is available in the system! To select, enter the command +COMMAND+", 
        "classactive");

INSERT INTO lng(ru, uz, en, flag) 
    VALUES ("Урок +CLASSES+ больше не доступен! Спасибо за участия!", 
        "+CLASSES+ fani tugatilindi! Ishtirokingiz uchun rahmat!", 
        "Lesson +CLASSES+ is no longer available! Thanks for participating!", 
        "classdeactive");

INSERT INTO classes(command, ru, uz, en) 
VALUES ("/life", "Life Show (Game Zone)", "Life Show (Game Zone)", "Life Show (Game Zone)");
INSERT INTO classes(command, ru, uz, en) 
VALUES ("/e_club", "English Club", "English Club", "English Club");