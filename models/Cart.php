<?php

namespace app\models;

use Exception;
use Yii;
use yii\base\Model;
use yii\base\UserException;
use yii\web\Cookie;

/**
 * @company BestArtDesign
 * @site http://bestartdesign.com
 * @author pest (pest11s@gmail.com)
 */

class Cart extends Model {

    const COOKIE_CART = 'cart_items';
    const COOKIE_RESTAURANT = 'cart_restaurant';

    private $_cart = array();
    private $_restaurantId = null;

    public function init() {
        parent::init();

        $cookies = Yii::$app->request->cookies;

        //Получаем данные корзины из куков
        if(isset($cookies[self::COOKIE_CART])) {
            parse_str($cookies->getValue(self::COOKIE_CART), $this->_cart);

            //Получаем ID ресторана
            if(isset($cookies[self::COOKIE_RESTAURANT])) {
                $this->_restaurantId = $cookies->getValue(self::COOKIE_RESTAURANT);
            }
        }
    }

    /**
     * Получение данных корзины
     *
     * @return array
     */
    public function getCart() {
        return $this->_cart;
    }

    /**
     * @return int
     */
    public function getRestaurantId() {
        return $this->_restaurantId;
    }


    /**
     * Добавление блюда в корзину
     *
     * @param $id
     * @param $amount
     *
     * @return $this
     * @throws Exception
     */
    public function addItem($id, $amount) {

        //Если блюда ещё нет в корзине
        if(!isset($this->_cart[$id])) {

            //Загружаем модель блюда
            /** @var Dish $dish */
            $dish = Dish::findOne($id);

            //Если блюдо не найдено
            if(!$dish) {
                throw new Exception('Невозможно добавить данное блюдо в коризну');
            }

            //Проверям, что блюдо из того же ресторана
            if(!$this->checkSameRestaurant($dish->restaurant_id)) {
                throw new Exception('Невозможно добавить блюдо из другого ресторана');
            }

            //Проверяем возможность рестораном принимать заказы
            $this->checkAvailableToOrder();

            $this->_cart[$id] = $amount;
        } else {
            $this->_cart[$id] += $amount;
        }

        //Сохраняем корзину в куки
        $this->saveCartToCookie();

        return $this;
    }

    /**
     * Проверка возможности рестораном принимать заказы
     *
     * @throws UserException
     */
    public function checkAvailableToOrder() {

        //Загружаем данные ресторана
        /** @var Restaurant $restaurant */
        $restaurant = Restaurant::findOne($this->getRestaurantId());

        //Проверяем возможность заказа в данном ресторане
        if(!$restaurant->order_available) {
            throw new UserException('В данный момент заказ недоступен в этом заведении. Воспользуйтесь другим заведением.');
        }

    }

    /**
     * Уменьшение количества блюда в корзине
     *
     * @param $id
     * @param $amount
     *
     * @return $this
     * @throws Exception
     */
    public function reduceItem($id, $amount) {

        //Если блюдо есть в корзине
        if(isset($this->_cart[$id])) {
            $this->_cart[$id] -= $amount;

            //Если количества блюда меньше либо равно нулю
            if($this->_cart[$id] <= 0) {
                unset($this->_cart[$id]);
            }
        }

        //Сохраняем корзину в куки
        $this->saveCartToCookie();

        return $this;
    }

    /**
     * Очистка корзины
     */
    public function clearCart() {
        $cookies = Yii::$app->response->cookies;
        $cookies->remove(self::COOKIE_CART);
        $cookies->remove(self::COOKIE_RESTAURANT);
        unset($cookies[self::COOKIE_CART], $cookies[self::COOKIE_RESTAURANT]);
    }

    /**
     * Проверка, что ресторан тот же
     *
     * @param $id
     *
     * @return bool
     */
    public function checkSameRestaurant($id) {
        if(is_null($this->_restaurantId)) {
            $this->_restaurantId = $id;
        }
        return $this->_restaurantId == $id;
    }

    /**
     * Сохранение корзины в куках
     *
     * @return $this
     */
    protected function saveCartToCookie() {
        Yii::$app->response->cookies->add(new Cookie([
            'name' => self::COOKIE_CART,
            'value' => http_build_query($this->getCart()),
            'expire' => time() + 86400,
        ]));
        Yii::$app->response->cookies->add(new Cookie([
            'name' => self::COOKIE_RESTAURANT,
            'value' => $this->_restaurantId,
            'expire' => time() + 86400,
        ]));

        return $this;
    }

    /**
     * Получения количества одного блюда в корзине
     *
     * @param $id
     *
     * @return int
     */
    public function getAmountOfSingleDish($id) {
        return isset($this->_cart[$id]) ? $this->_cart[$id] : 0;
    }

}