README
======

SLDAO - Service Layer and Data Access Object

Has unit tests: yes
Vendors: Symfony2, doctrine-common


English Version
-----------------

In progress ...

Russian Version
-----------------

SLDAO - Service Layer and Data Access Object. Другой аббривиатуры не смог выдумать. При разработке серьезных
проектов нельзя использовать концепцию CRUD Symfony 2. Необходимо что-то более "существенное". Аналогичные попытки
предпринимают FOS, KnpLabs. Но нет какого-то единого интерфейса, и при создании новой сущности приходиться все создавать
сначала.

Данныая архитектура инкапсулирует работу с объектом на уровне доступа к данным. Работа с данными происходит через менеджер объекта.
Это есть ни что иное как DAO. Создана базовая реализация менеджера DoctrineAbstractManager для "доктриновских менеджеров".
Он использует интерфейс ObjectManager, который единый для EntityManager и DocumentManager. Для использования Propel
необходимо создать аналогичный класс, например PropelManagerAbstract.

Менеджер не должен делать ничего лишнего. Вы можете его унаследовать и реализовать какие-то кастомные методы поиска. Я не
согласен с контрибьюторами FOSCommentBundle, которые используют в менеджере EventDispatcher. Это уже бизнес логика приложения.

За бизнес логику отвечает Service. Я создал интерфейс ServiceInterface, который подчеркивает и подсказывает, что при наличии
сложной логики приложения при сохранении или удалении объекта, вы легко реализуете ее здесь, при этом не загрязняя DAO.
Именно здесь разумно использовать EventDispatcher, Logger, реализовывать частичное сохранение данных. Например:

```php
<?php
.....
Class ProductService extends Service
{
    ....
    public function save($object)
    {
        $prePersistEvent = new PrePersistEvent($product);
        $this->dispatcher->dispatch('product_pre_persist', $prePersistEvent);
        if (!$prePersistEvent->isAborted()) {
            parent::save($object);
        }
        $postPersistEvent = new PostPersistEvent($product);
        $this->dispatcher->dispatch('product_post_persist', $postPersistEvent);
    }

    public function changeProductName($newName, $product)
    {
        ....
        $product->setName($newName);
        $this->save($product);
    }
}

```

Приэтом при отсутствии необходимости чего-то кастомного, мы используем базовую реализацию.
Практическое применение для open source будет скоро выложено. Любая дисскусия приветствуется.
