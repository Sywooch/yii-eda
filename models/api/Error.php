<?php
/**
 * @company BestArtDesign
 * @site http://bestartdesign.com
 * @author pest (pest11s@gmail.com)
 */

namespace app\models\api;

class Error {

    const ERR_MISSING_REQUIRED_PARAM = 101;     //Не передан обязательный параметр
    const ERR_METHOD = 102;                     //Неизвестный метод
    const ERR_LOGIN = 103;                      //Неверный логин или пароль
    const ERR_SESSION = 104;                    //Неверный ключ сессии
    const ERR_SESSION_EXPIRE = 105;             //Сессия устарела
    const ERR_RESTAURANT_MISSING = 106;         //Пользователю не присвоен ресторан
    const ERR_ORDER_UNKNOWN = 107;              //Не найден заказ
    const ERR_SAVING = 108;                     //Ошибка сохранения
    const ERR_DENY = 109;                       //Доступ запрещен

    const ERR_UNKNOWN = 999;

}