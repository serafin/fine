# Model

Interfejs obiektowy do pracy z tabelami (Active Record, ORM, Table Data Gateway).

## Wymagania

- Modele znajdują się w katalogu `app/m`.
- Nazwa modelu odpowiada nazwie tabeli.
- Nazwy pól modelu odpowiadają nazwom pól tabeli w bazie danych.
- Nazwa modelu nie może zawierać znaku podkreślenia.
- Nazwy pól w tabelach są unikalne w całej bazie danych.
- Nazwa klucza podstawowa modelu to `nazwamodelu_id`.
- Nazwa klucza obcego do innego modelu to `nazwamodelu_id_nazwainnegomodelu`.
- Model dziedziczą po klase `f_m` (bezpośrednio lub pośrednio).
- Pole przetrzymujące czas utworzenia rekordu w formacie TIMESTAMP ma nazwe `nazwamodelu_insert`.
- Pole przetrzymujące czas aktualizacji rekordu w formacie TIMESTAMP ma nazwe `nazwamodelu_update`
- Pole przetrzymujące ilość powiązanych rekordów z innej tabeli (powiązanych innych modeli) to `nazwamodelu_count_nazwainnegomodelu`

## Zarys klasy

```php
<?php

class f_m
{

    /**
     * Zwraca obiekt polaczanie za baz danych typu f_db_mysql
     */
    public function db(); // zwraca obiekt f_db_mysql
    
    /** 
     * Pola
     * - uwzgledniane w zapytaniach SELECT, INSERT, UPDATE
     * - przy pobieraniu i ustawianiu wartosci modelu dla Active Record np. metoda `val`
     */
    public function field($absField = null); // pobiera/ustawia pola
    public function addField($asField);      // dodaje pole/pola
    public function removeField($asField);   // usuwa pole/pola
    public function fieldBack();             // Przywraca ostatni stan pol
    public function defaultField();          // Ustawia pola na standardowe

    /**
     * Wartość modelu
     */
    public function val($aKeyValue = null, $asRestrictionField = null); // Pobiera, ustawia wartosci dla Active Record
    public function fieldAndVal($aKeyValue); // Ustawia klucze tablicy jako pola i ustawia wartosci
    public function data();                  // Pobiera wyselekcjonowane dane, alias (tylko getter) dla wlasnosci `$this->_`
    public function id($sValue = null);      // Pobiera ustawia id rekordu
    
    /**
     *  Parametry dla zapytan
     */
    public function param($asKey = null, $sValue = null); // Pobiera/ustawia parametr/parametry
    public function paramId($isId = null);                // Pobiera/ustawia parametr id rekordu
    public function isParam($sKey);                       // Sprawdza czy parametr istnieje
    public function removeParam($asKey = null);           // Usuwa parametr/parametry

    /**
     * Selekcjonowanie
     */
    public function select($aisParam = null);   // Rekord - Active Record (odpowiednik f_db_mysql->row)
    public function selectAll($aParam = null);  // Rekordy (odpowiednik f_db_mysql->rows)
    public function selectCol($aParam = null);  // Kolumna (odpowiednik f_db_mysql->col)
    public function selectCols($aParam = null); // Dwie kolumny jako tablica asocjacyjna (odpowiednik f_db_mysql->cols)
    public function selectVal($aParam = null);  // Wartosc (odpowiednik f_db_mysql->val)
    public function selectCount($aParam = null, $sExpr = '*'); // Oblicza liczbe rekordow
    public function selectLoop($aParam = null); // Pobieranie rekordow w petli (odpowiednik f_db_mysql->query)
    public function selectNext();               // Pobieranie rekordow w petli (odpowiednik f_db_mysql->fetch)
    public function selectInserted();           // Pobiera ostatnio dodany rekod


    /**
     * Pobieranie - to samo co selekcjonowanie + wyselekcjonowane dane sa zwracane
     */
    public function fetch($aisParam = null);   // Odpowiednik select
    public function fetchAll($aParam = null);  // Odpowiednik selectAll
    public function fetchCol($aParam = null);  // Odpowiednik selectCol
    public function fetchCols($aParam = null); // Odpowiednik selectCols
    public function fetchVal($aParam = null);  // Odpowiednik selectVal
    public function fetchCount($aParam = null, $sExpr = '*'); // odpowiednik selectCount
    public function fetchNext();               // Odpowiednik selectNext
    public function fetchInserted();           // Odpowiednik selectinserted

    /** 
     * Dodawanie, aktualizacja, usuwanie rekordow 
     */
    public function insert($aData = null);    // Dodaje rekord
    public function insertAll($aData = null); // Dodaje wiele rekordow jednym zapytaniem
    public function update($aData = null, $aisParam = null); // aktualizuje rekord/rekordy
    public function updateAll($aData = null); // aktualizuje wszystkie rekordy w tabeli
    public function delete($aisParam = null); // usuwa rekord/rekordy
    public function deleteAll();              // Usuwa wszystkie rekordy z tabeli
    public function save($aData = null, $iId = null); // Jezeli jest `id()` modelu to `update()`, inaczej `insert()`

    /**
     *  Relacje
     */

    // W wlasciwym modelu w ciele metody definiowane sa niestandardowe relacje
    // Standardowych nie trzeba definiowac, ORM wie co z czym jest polaczone
    public function relations();

    // Definiowanie relacji (lub pobieranie)
    public function relation($sName, $sThisField = null, $sRelatedField = null, $asCondition = null);

    // JOIN - metoda dodaje do parametrow pole join z metadanymi polaczenia, usuniecie parametru join wylaczy relacje
    public function join($sRelation, $asField = null, $sModel = null, $sJoinAlias = null, $sModelAlias = null);

    // LEFT JOIN
    public function joinLeft($sRelation, $asField = null, $sModel = null, $sJoinAlias = null, $sModelAlias = null);

    // Model zalezny, dalej mozna odwolac sie do takie modelu przez `__get($sDependentModelName)`
    public function dependent($sDependentModelName);

}
?>

```
## Przykładowe klasy modeli

```php
<?php

class m_user extends f_m
{

    public $user_id;
    public $user_name;
    public $user_email;
    public $user_pass;

}

class m_post extends f_m
{

    public $post_id;
    public $post_id_user;
    public $post_insert;
    public $post_title;
    public $post_text;
    public $post_count_comment;

}

class m_comment extends f_m
{

    public $comment_id;
    public $comment_id_post;
    public $comment_insert;
    public $comment_ip;
    public $comment_author;
    public $comment_text;

}

?>
```

## Nowosci

### param

Doszla nowa metoda param, ktora przetrzymuj

## Operacje na rekordach

### Dodawanie

```php
<?php

$oUser = new m_user();
$oUser->user_name  = 'Jan';
$oUser->user_pass  = '1234';
$oUser->user_email = 'jan1980@adres.pl';
$oUser->save();
$oUser->selectInserted();
// wykonywane jest zapytanie SELECT * FROM model WHERE model_id = LAST_INSERT_ID()
// dzieki czemu standardowe wartosci zdefiniowane w MySQL są dostępne w modelu

$oPost = new m_post();
$oPost->post_id_user = $oUser->user_id;
$oPost->post_insert  = time();
$oPost->post_title   = 'Pierwszy post na blogu';
$oPost->post_text    = 'Treść pierwszego psota na blogu';
$oPost->save();

?>
```

### Selekcjonowanie

#### Jendnego rekordu

##### Według klucza podstawowego

```php
<?php

$oPost = new m_post();
$oPost->select(1234); 
                                           
echo $oPost->post_id;
echo $oPost->post_title;
echo $oPost->post_text;

?>
```

Na przekazywanej wartości do metody, automatycznie wykonywana jest funkcji mysql_real_escape_string przed wykonaniem zapytania.

##### Według wartości kolumn

```php
<?php

//SELECT post_id, post_id_user, post_insert, post_title, post_text, post_count_comment FROM post WHERE post_title = 'New post'
$oPost = new m_post();
$oPost->select(array('post_title' => 'New post'));

//SELECT post_id, post_id_user, post_insert, post_title, post_text, post_count_comment FROM post WHERE post_title = 'New post' AND post_time = '123456789'
$oPost = new m_post();
$oPost->select(array('post_title' => 'New post', 'post_time' => 123456789));

?>
```

Metoda `select` przypisuje automatycznie wartości wyselekcjonowanego rekordu do pól obiektu modelu.
Metoda `val` pobiera i ustawia pola modelu.

Metody `select*` nie zwracaja juz pobranych danych.
Model teraz przetrzymuje dane.
Aby pobrac dane z modelu nalezy sie odwolac do wlasnosci `_` lub metody `data()`.


#### Wiele rekordów

Do selekcji wielu rekordów służy metoda selectAll.
```php
<?php

//SELECT post_id, post_id_user, post_insert, post_title, post_text, post_count_comment FROM post WHERE post_id_user = '1'
$this->v->post = m_post::_()
                    ->selectAll(array('post_id_user' => 1))
                    ->_;

?>
```

#### Tylko wybranych pól

```php
<?php

//SELECT post_title, post_count_comment FROM post WHERE post_id_user = '1'
$this->v->post = m_post::_()
                    ->field(array('post_title', 'post_count_comment')) //lub pola podane jako string gdzie kolejne pola oddzielone znakiem spacji
                    ->selectAll(array('post_id_user' => 1))
                    ->_;

?>
```

#### Jednej kolumny

np. Wszystkie ID postow uzytkownika o ID 1 jako tablica jednowymiarowa

```php
<?php

//SELECT post_id FROM post WHERE post_id_user = '1'
$oPost = new m_post();
$oPost->field('post_id');
$aPostUser = $oPost->col(array('post_id_user' => 1))->_;
foreach ($aPostUser as $post_id) {
    echo $post_id . '<br />';
}

?>
```

#### Dwóch kolumn

np. Wszystkie ID i czas postow uzytkownika o ID 1 jako tablica jednowymiarowa gdzie ID to klucz a czas wartość elementu tablicy

```php
<?php

//SELECT post_id, post_insert FROM post WHERE post_id_user = '1'
$oPost = new m_post();
$oPost->field('post_id post_insert');
$aPostUser = $oPost->cols(array('post_id_user' => 1))->_;
foreach ($aPostUser as $post_id => $post_insert) {
    echo $post_id . ' - ' . $post_insert . ' <br />';
}

?>
```
#### Wartości jednego pola w jednym rekordzie

                                 np. Tytuł postu o ID 1234

```php
<?php
 //SELECT post_title FROM post WHERE post_id = '1234'
 $oPost = new m_post();
 $oPost->field('post_title');
 $sTitle = $oPost->one(1234);
?>
```

### Selekcjonowanie zaawansowane
```php
<?php
 $oPost = new m_post();
 $aPost = $oPost->selectAll(array(
         'post_id_user'   => array(1,3,5),
         'order by'       => 'post_id DESC',
         'field'          => array('COUNT(*) as xxx', 'post_id_user'),
         'group by'           => 'post_id_user',
         'having'             => 'xxx > 1',
         'offset'             => 10,
         'limit'          => 10,
         'post_insert|>=' => time() - 60*60*24*7
 ));
 //Wygeneruje zapytanie:
 //SELECT post_id, post_insert, post_title, post_name, post_text, post_count_comment
 //FROM post
 //WHERE post_id_user IN('1','3','5') AND post_insert >= '1232733645'
 //GROUP BY post_id_user
 //HAVING xxx > 1
 //ORDER BY post_id DESC
 //LIMIT 10
 //OFFSET 10

?>
```

                         [b]selekcjonowanie według zapytania SQL[/b]
                         metoda selectBySQL pobrany rekord zapisuje do pól obiektu modelu
```php
<?php
 $oPost = new m_post();
 $aPost = $oPost->selectBySQL("
         SELECT post_id, post_insert, post_title, post_name, post_text, post_count_comment
         FROM post
         WHERE post_id_user IN('1','3','5') AND post_insert >= '1232733645'
         GROUP BY post_id_user
         HAVING xxx > 1 ORDER BY post_id DESC
         LIMIT 10
         OFFSET 10
 ");
?>
```

### Aktualizacja
```php
<?php
 $oUser = new m_user();
 $oUser->select(1);
 $oUser->user_name  = 'Jan Kowalski';
 $oUser->user_pass  = '12345';
 $oUser->user_email = 'jan1980@adres.pl';
 $oUser->save();
?>
```
```php
<?php
 $oUser = new m_user();
 $oUser->user_id    = 1;
 $oUser->user_name  = 'Jan Kowalski';
 $oUser->user_pass  = '12345';
 $oUser->user_email = 'jan1980@adres.pl';
 $oUser->save();
?>
```
```php
<?php
 $oUser = new m_user();
 $oUser->id(1);
 $oUser->user_name  = 'Jan Kowalski';
 $oUser->user_pass  = '12345';
 $oUser->user_email = 'jan1980@adres.pl';
 $oUser->save();
?>
```


### Usuwanie
```php
<?php
 $oUser = new m_user();
 $oUser->delete(1);
?>
```
```php
<?php
 $oUser = new m_user();
 $oUser->user_id = 1;
 $oUser->delete();
?>
```
```php
<?php
 $oUser = new m_user();
 $oUser->id(1);
 $oUser->delete();
?>
```

                         Funkcja delete nie usuwa wszystkich rekordów bez warunku - kiedy nie podamy ID rekordu w argumencie lub wcześniej nie ustawimy wartość klucza podstawowego
```php
<?php
 $oUser = new m_user();
 $oUser->delete();
?>
```
                         W tym wypadku żadne rekordy nie zostaną usunięte. Ta funkcjonalność pełni zabezpieczenie przed błędem np. gdy jako argument wartość null

                         Aby usunać wszystkie rekordy z tabeli należy użyć metody deleteAll

## ORM - Mapowanie obiektowo-relacyjne
                 Mapowanie obiektowo-relacyjne (ang. Object-Relational Mapping ORM) to sposób odwzorowania obiektowej architektury systemu informatycznego na bazę danych (lub inny element systemu) o relacyjnym charakterze. Implementacja takiego odwzorowania stosowana jest m.in. w przypadku, gdy tworzony system oparty jest na podejściu obiektowym, a system bazodanowy (System Zarządzania Bazą Danych) operuje na relacjach. [size=10](Żródło: [url]http://pl.wikipedia.org/wiki/Mapowanie_obiektowo-relacyjne[/url])[/size]

### Relacja jeden do wiele (post ma jednego użytkownika i użytkownika ma wiele postów)
                         Powiązania pomiędzy modelami są tworzone po przez pola: $_ref i $_dep
                         $_ref oznacza, że model posiada w relacji jeden model   (ref (reference) czyli referencja)
                         $_dep oznacza, że model posiada w relacji wiele modelów (dep (dependent) czyli zależność)

```php
<?php
 class m_user extends f_m
 {
         public $user_id;
         public $user_name;
         public $user_email;
         public $user_pass;
         protected $_dep = array('post');

 }

 class m_post extends f_m
 {
         public $post_id;
         public $post_id_user;
         public $post_insert;
         public $post_title;
         public $post_text;
         protected $_ref = array('user');
 }
?>
```

                         Jeżeli model A posiada w relacji jeden model B to w klasie A jest zdefiniowana zmienna $_ref wskazująca na model B, wtedy model B posiada zmienną $_dep wskazującą na mode A.
                         $_dep nie musi być koniecznie zdefiniowany jeśli nie jest wykorzystywany wtedy $_ref będzie działać poprawnie. Jednak jeżeli istnieje relacja A do B i zdefiniujemy dep w B do A i nie zdefiniujemy ref w A do B, to dep nie będzie działał, ponieważ dep korzysta z ref

                         [b]Selekcja posta i użytkownika tego posta (JOIN)[/b]
```php
<?php
 $oPost = new m_post();
 $oPost->join('user');
 $oPost->select(1);
 //SELECT post_id_user, post_insert, post_title, post_text, post_count_comment, user.*
 //FROM post, user WHERE post_id_user = user_id AND post_id = '1'
?>
```

                         jako drogi parametr metody join można podać tablice pól (lub string - pola oddzielone spacją) które mają być wyselekcjonowane z tabeli user

                         [b]Selekcja posta i użytkownika tego posta (LEFT JOIN)[/b]
```php
<?php
 $oPost = new m_post();
 $oPost->joinLeft('user');
 $oPost->select(1);
 //SELECT post_id_user, post_insert, post_title, post_text, post_count_comment, user.*
 //FROM post LEFT JOIN user ON ( post_id_user = user_id ) WHERE post_id = '1'
?>
```

                         [b]Selekcja uzytkownika i posty tego użytkownika[/b]
```php
<?php
 $oUser = new m_user();
 $oUser->select(1);
 $aPost = $oUser->post->selectAll();
 //SELECT user_id, user_name, user_email, user_pass WHERE user_id = '1'
 //SELECT post_id_user, post_insert, post_title, post_text, post_count_comment FROM post WHERE post_id_user = '1'
?>
```

### Relacja jeden do jeden (uzytkownik ma jeden profil i profil ma jednego użytkownika)
                         Relacja jeden do jeden polega na usteleniu w modelu A zmiennej $_ref wskazującej na model B i w modelu B także zmiennej $_ref wskazującej na model A.
```php
<?php
 class m_user extends f_m
 {
         public $user_id;
         public $user_name;
         public $user_email;
         public $user_pass;
         protected $_ref = array('profil' => array('user_id', 'profil', 'profil_id'));
         //                       --1---            ---2---    --3---    ----4----
         //1 - nazwa referencji i jednocześnie nazwa modelu wykorzystywana przez metody join i joinLeft
         //2 - nazwa pola tego modelu który jest w relacji
         //3 - nazwa tabeli obcej
         //4 - nazwa pola z tabeli obcej które jest w relacji
 }
 class m_profil extends f_m
 {
         public $profil_id;
         public $profil_info1;
         public $profil_info2;
         protected $_ref = array('user' => array('profil_id', 'user', 'user_id'));
 }
 $oUser = new m_user();
 $oUser->join('profil');
 $oUser->select(1);
 //SELECT user_id, user_name, user_email, user_pass, profil.* FROM user, profil WHERE user_id = '1' AND user_id = profil_id
?>
```

### Relacja wiele do wiele (post ma wiele kategorii i kategoria ma wiele postów)
                         Relacja wiele do wiele następuje przez dodatkową tabele powiązań
```php
<?php
 class m_post extends f_m
 {
         public $post_id;
         public $post_insert;
         public $post_title;
         public $post_text;
         protected $_dep = array('categorypost');
 }
 class m_category extends f_m
 {
         public $category_id;
         public $category_name;
         protected $_dep = array('categorypost');
 }
 class m_categorypost extends f_m
 {
         public $categorypost_id_category;
         public $categorypost_id_post;
         protected $_ref = array('category', 'post');
 }
?>
```
                         np. wybieranie wszystkich postów i ich kategorii
```php
<?php
 $oCategorypost = new m_categorypost();
 $oCategorypost->field(flase); //oznacza, że żadne pola tego modelu nie mają być wybierana
 $oCategorypost->join('post', 'post_name');
 $oCategorypost->join('category', 'category_name');
 $oCategorypost->selectAll();
 //SELECT post_name, category_name FROM categorypost, post, category
 //WHERE categorypost_id_category = category_id AND  categorypost_id_post = post_id
?>
```
### Łączenie dołączonego modelu z innym obcym modelem
                         np. gdy post ma uzytkownika a uzytkownik fotke
```php
<?php
 class m_post extends f_m
 {
         public $post_id;
         public $post_id_user;
         public $post_insert;
         public $post_title;
         public $post_text;
         protected $_ref = array('user');
 }
 class m_user extends f_m
 {
         public $user_id;
         public $user_name;
         public $user_email;
         public $user_pass;
         protected $_ref = array('img');
 }
 class m_img extends f_m
 {
         public $img_id;
         public $img_desc;
 }
 $oPost = new m_post();
 $oPost->join('user', 'user_name');
 $oPost->join('img', 'img_desc', 'user'); //trzeci parametr - nazwa obcego modelu z którym ma nastąpić połączenie lub null gdy z aktualnym modelem
 $oPost->select(1);
 //SELECT post_id, post_id_user, post_insert, post_title, post_text, user_name, img_desc
 //FROM post, user, img
 //WHERE post_id = '1' AND post_id_user = user_id AND user_id_img = img_id
?>
```
### Łączenie modelu z innym wiele razy
                         np. prywatna wiadomość posiada nadawce i odbiorce
```php
<?php
 class m_user extends f_m
 {
         public $user_id;
         public $user_name;
         public $user_email;
         public $user_pass;
         protected $_dep = array('pm_from', 'pm_to');
 }
 class m_pm extends f_m
 {
         public $pm_id;
         public $pm_id_user_form;
         public $pm_id_user_to;
         public $pm_text;
         protected $_ref = array('user_from', 'user_to');
 }
?>
```

                         [b]pobierania widomosci o ID 1[/b]

```php
<?php
 $oPm = new m_pm();
 $oPm->field('pm_text');
 $oPm->join(array('f' => 'user_from'), array('userfrom' => 'user_name'));
 $oPm->join(array('t' => 'user_to'  ), array('userto'   => 'user_name'));
 $oPm->select(1);
 //SELECT pm_text, f.user_name as userfrom, t.user_name as userto
 //FROM pm, user as f, user as t
 //WHERE pm_id = '1' AND pm_id_user_from = f.user_id AND pm_id_user_to = t.user_id
?>
```

                         [b]pobranie wszystkich wiadomości które zostały wysłane przez usera o ID 1[/b]

```php
<?php
 $oUser = new m_user();
 $oUser->user_id = 1;
 $oUser->pm_from->selectAll(); //
 //SELECT pm_id, pm_id_user_form, pm_id_user_to, pm_text FROM pm WHERE pm_id_user_from = '1'
?>
```
### Łączenie modelu z innym wiele razy i łączenie dołączonego modelu z innym obcym modelem
```php
<?php
 class m_pm extends f_m
 {
     public $pm_id;
     public $pm_id_user_form;
     public $pm_id_user_to;
     public $pm_text;
     protected $_ref = array('user_from', 'user_to');
 }
 class m_user extends f_m
 {
     public $user_id;
     public $user_name;
     public $user_email;
     public $user_pass;
     protected $_dep = array('pm_from', 'pm_to');
         protected $_ref = array('img');
 }
 class m_img extends f_m
 {
     public $img_id;
     public $img_desc;
 }
?>
```

#### pobieranie widomości o ID 1 z dołączeniem użytkownika który wysłał wiadomość i użytkownika który otrzymał wiadomość, dodatkowo dołączenie obrazka użytkownika do którego jest wysłana wiadomość

```php
<?php
 $oPm = new m_pm();
 $oPm->field('pm_text');
 $oPm->join(array('f' => 'user_from'), array('userfrom' => 'user_name'));
 $oPm->join(array('t' => 'user_to'  ), array('userto'   => 'user_name'));
 $oPm->join('img', 'img_desc', array('t' => 'user'));
 $oPm->select(1);
 //SELECT pm_text, f.user_name as userfrom, t.user_name as userto, img_desc
 //FROM pm, user as f, user as t, img
 //WHERE
 //      pm_id = '1'
 //      AND pm_id_user_from = f.user_id
 //      AND pm_id_user_to   = t.user_id
 //      AND t.user_id_img   = img_id
?>
```

#### pobieranie widomosci o ID 1 z dołączeniem użytkownika który wysłał wiadomość i użytkownika który otrzymał wiadomość, dodatkowo dołączenie obrazka do każdego użytkownika

```php
<?php
 $oPm = new m_pm();
 $oPm->field('pm_text');
 $oPm->join(array('f'  => 'user_from'), array('userfrom'    => 'user_name'));
 $oPm->join(array('t'  => 'user_to'  ), array('userto'      => 'user_name'));
 $oPm->join(array('fi' => 'img'      ), array('userfromimg' => 'user_name'), array('f' => 'user'));
 $oPm->join(array('ti' => 'img'      ), array('usertoimg'   => 'user_name'), array('t' => 'user'));
 $oPm->select(1);
 //SELECT pm_text, f.user_name as userfrom, t.user_name as userto, fi.img_desc as userfromimg, ti.img_desc as usertoimg
 //FROM pm, user as f, user as t, img as fi, img as ti
 //WHERE
 //      pm_id = '1'
 //      AND pm_id_user_from = f.user_id
 //      AND pm_id_user_to = t.user_id
 //      AND f.user_id_img = fi.img_id
 //      AND t.user_id_img = ti.img_id
?>
```





## Obsługa pól przechowujących informacje o liczbie rekordów w relacji
```php
<?php
 class m_post extends f_m
 {
         public $post_id;
         public $post_id_user;
         public $post_insert;
         public $post_title;
         public $post_text;
         public $post_count_comment;
         protected $_dep = array('comment');
 }

 class m_comment extends f_m
 {
         public $comment_id;
         public $comment_id_post;
         public $comment_insert;
         public $comment_ip;
         public $comment_author;
         public $comment_text;
         protected $_ref = array('post');
         public function insert()
         {
                 parent::insert();
                 $this->increment('post'); // jako argument nazwa referencji
         }                             // pole w modelu post musi sie odpowiednio nazywać (post_count_comment)
         public function delete()
         {
                 parent::delete();
                 $this->decrement('post');
         }
 }
?>
```

                 Lub korzystając z funkcji counter która oblicza dokładną ilości rekordów w relacji (SELECT COUNT(*))
```php
<?php
 /* ... */
 class m_comment extends f_m
 {
         /* ... */
         public function insert()
         {
                 parent::insert();
                 $this->counter('post');
         }
         public function delete()
         {
                 parent::delete();
                 $this->counter('post');
         }
 }
?>
```

## Floodowanie
                 Do zabezpieczenia przed floodowaniem służy metoda flood modelu, ktora zwraca true jeżeli nie wyselekcjonuje rekordu dodanego w czasie podanym jako parametr (w sekundach)
                 Blokade zdefiniować najlepiej w samym modelu przeciążając metodę insert
```php
<?php
 class m_post extends f_m
 {
         /* ... */
         public function insert()
         {
                 if ($this->flood(15)) {
                         return parent::insert();
                 }
                 return 2;
         }
 }
?>
```
```
                 f_m::insert zwraca 0 kiedy sukces lub 1 gdy blad, liczby <2,n> wolne do użycia

                 Aby ustawić blokade na danego użytkownika tzn. użytkownik może co 15 sekund dodawać nowy rekord, trzeba rozszerzyć warunek sprawdzania:
```php
<?php
 class m_post extends f_m
 {
         /* ... */
         public function insert()
         {
                 if ($this->flood(15, array('post_id_user' => $this->post_id_user))) {
                         return parent::insert();
                 }
                 return 2;
         }
 }
?>
```


## Zliczanie ilości rekordów

                 Do zliczania ilości rekordów służy metoda count
### Ilość wszystkich rekordów
```php
<?php
 $oPost = new m_post();
 echo $oPost->count();
 //SELECT COUNT(*) FROM post
?>
```
### Sprawdzanie czy rekord o ID 1234 istnieje
```php
<?php
 $oPost = new m_post();
 echo (boolean) $oPost->count(1234);
 //SELECT COUNT(*) FROM post WHERE post_id = '1234'
?>
```
### Zliczanie ilości postów uzytkwonika o ID 1234
```php
<?php
 $oPost = new m_post();
 echo  $oPost->count(array('post_id_user' => 1234));
 //SELECT COUNT(*) FROM post WHERE post_id_user = '1234'
?>
```


 [h1]Stronnicowanie[/h1]
         Do stronnicowania służy klasa f_paging, która na podstawie ilości wszystkich rekordów, limitu i nr aktualnej strony oblicza ilosc wszystkich podstron, offset, nastepna strone, poprzednia strone i generuje linki do podstron
```php
<?phpphp
 /**
  *
  *
  */
 $iCount  = db::one("SELECT COUNT(*) FROM table"); //liczba wszystkich rekordów
 $iLimit  = 10;                                    //limit rekordow na stronie
 $iGet    = 2;                                     //nr parametru w adresie, który odpowiada numerowi aktualnej strony, wykorzystywany także do generowania linków
 $oPaging = new f_paging($iCount, iLimit, iGet);

 echo $oPaging->page;                              //liczba wszystkich stron
 echo $oPaging->current;                           //nr aktualnej strony (liczone od zera), podana liczba jest prawidlowa w prówaniu z tą w adresie która nie koniecznie musi być prawidlowa
 echo $oPaging->offset;                            //offset
 echo $oPaging->limit;                             //limit a stronie

 $aData = db::rows("SELECT * FROM table LIMIT $oPaging->offset, $oPaging->limit");
?>
```
## Praktyczny przykład użycia
```php
<?php
 //URI: /post/all
 //Controller
 $oPost   = new m_post();
 $oPaging = new f_paging($oPost->count(), 10, 2);
 $this->v->post   = $oPost->selectAll(array('paging' => $oPaging));
 $this->v->pagign = $oPaging;
 //View
 foreach ($this->post as $post) {
         echo "{$post['post_id']}<br />";
 }
 echo $this->paging;
?>
```

## Zmiana standardowych adresów linków
         Jako trzeci parametr konstruktora podawany jest indeks parametru w adresie, który jest wykorzystywany do generowania link. Np. jeżeli parametr = 2 to linki zostaną przepisane na zasadzie [nobr]"/{$_GET[0]}/{$_GET[1]}/" . {numer_podstrony}[/nobr]

         Ten standardowy link można zmienić, można także dopisać coś nakońcu linku np. link do elementu na stronie [nobr]("/{$_GET[0]}/{$_GET[1]}/123/#sekcja")[/nobr]

         Standardowo klasa f_paging ma ustawioną klase f_h_pagingNum do generowania linków. Do klasy renderującej linki można przekazać dowolne ustawienia ustalając je w obiekcie stronnicowania.

         Klasa f_h_pagingNum posiada takie ustawienia jak:
         uriBegin - początek linku
         uriEnd   - koniec linku
         width    - szerokość - ilości linków od lewej i  od prawej strony linku aktualnej strony
```php
<?php
 //Controller
 $oPost   = new m_post();
 $oPaging = new f_paging($oPost->count(), 10, 2);
 $oPaging->uriBegin = '/controller/akcja/';
 $oPaging->uriEnd   = '/#sekcja';
 $this->v->pagign   = $oPaging;
 //View
 echo $this->paging;
?>
```

## Tworzenie własnego wyglądu linków
         Klasa f_paging posiada metode "view" gdzie jako parametr należy podać nazwe klasy jako string lub jako obiekt klasy renderującej linki. Klasa generująca linki musi posiadać metodę o nazwie render (jezeli podajemy jako string to render musi być metodą statyczną), która przyjmuje jako parametr obiekt stronicowania.

         Jeśli tworzymy nową klasę, która będzie wykorzystywana tylko dla danej aplikacji to jej nazwa powinna sie zaczynać od "a_h_paging".

         Przykładowa klasa generująca linki
```php
<?phpphp
 class f_h_pagingPrevNext
 {

         public static function render($oPaging)
         {
                 $sUriBegin = null;   //|
                 $sUriEnd   = null;   //| Standardowe ustawienia
                 $sPrev     = '&lt;'; //|
                 $sNext     = '&gt;'; //|

                 if ($oPaging->uriBegin !== null) {   //|
                         $sUriBegin = $oPaging->uriBegin; //|
                 }                                    //|
                 if ($oPaging->uriEnd   !== null) {   //|
                         $sUriEnd = $oPaging->uriEnd;     //|
                 }                                    //|
                 if ($oPaging->linkPrev !== null) {   //|
                         $sPrev   = $oPaging->linkPrev;   //| Pobieranie ustawień z obiektu klasy f_paging
                 }                                    //|
                 if ($oPaging->linkNext !== null) {   //|
                         $sNext   = $oPaging->linkNext;   //|
                 }                                    //|

                 if (empty($sUriBegin)) {                 //|
                         $sUriBegin = reg::get('uriIndex');   //|
                         for($i=0; $i<$oPaging->uri; $i++){   //|
                                 if(isset($_GET[$i])) {           //| Generowanie początkowego fragmentu linku na
                                         $sUriBegin .= $_GET[$i].'/'; //| podstawie parametru uri obiektu stronnicowania
                                 }                                //| (trzeci parametr konstruktora klasy stronnicowania)
                         }                                    //|
                 }                                        //|

                 return // Zwracanie odpowiedniego kodu html
                         '<div class="paging"><ul>'
                         .(($oPaging->prev !== null) ? '<li class="back"><a href="' . $sUriBegin . $oPaging->prev . $sUriEnd . '">'.$sPrev.'</li>' : '')
                         .(($oPaging->next !== null) ? '<li class="next"><a href="' . $sUriBegin . $oPaging->next . $sUriEnd . '">'.$sNext.'</li>' : '')
                         .'</ul></div>';
         }

 }
?>
```

         Zmiana klasy renderującej linki
         (wersja 1)
```php
<?php
 //URI: /post/all
 //Controller
 $oPost   = new m_post();
 $oPaging = new f_paging($oPost->count(), 10, 2);
 $oPaging->view('f_h_pagingPrevNext');                                // <-----
 $this->v->post   = $oPost->selectAll(array('paging' => $oPaging));
 $this->v->pagign = $oPaging;
 //View
 foreach ($this->post as $post) {
         echo "{$post['post_id']}<br />";
 }
 echo $this->paging;
?>
```

         lub w widoku:
         (wersja 2)
```php
<?php
 //URI: /post/all
 //Controller
 $oPost   = new m_post();
 $oPaging = new f_paging($oPost->count(), 10, 2);
 $this->v->post   = $oPost->selectAll(array('paging' => $oPaging));
 $this->v->pagign = $oPaging;
 //View
 $this->paging->view('f_h_pagingPrevNext');                           // <-----
 foreach ($this->post as $post) {
         echo "{$post['post_id']}<br />";
 }
 echo $this->paging;
?>
```

         (wersja 3)
```php
<?php
 //URI: /post/all
 //Controller
 $oPost   = new m_post();
 $oPaging = new f_paging($oPost->count(), 10, 2);
 $this->v->post   = $oPost->selectAll(array('paging' => $oPaging));
 $this->v->pagign = $oPaging;
 //View
 foreach ($this->post as $post) {
         echo "{$post['post_id']}<br />";
 }
 echo f_h_pagingPrevNext::render($this->paging);                      // <-----
?>
```

 [h1]Dodatki[/h1]
## Obsługa uploadowanych plików z wykorzystaniem klasy f_upload
### Jeden plik
```
                 Jeżeli istnieje tylko jeden <input type="file"> w formularzu to nie trzeba podawać jego nazwy, f_upload automatycznie ją wykryje
```php
<?php
 $oUpload = new f_upload();
 if ($oUpload->is()) {
         $oUpload->move('./_upload/'.$oUpload->name());
 }
?>
```
### Jeden plik i wiele elementów
```php
 <input type="file" name="image" />
 <input type="file" name="attachment" />
```
```php
<?php
 $oUpload = new f_upload('image');
 if ($oUpload->is()) {
         $oUpload->move('./_upload/'.$oUpload->name());
 }
?>
```
### Wiele plików
```
```php
<?php
 foreach (f_upload::each() as $oUpload) {
         if ($oUpload->is()) {
                 $oUpload->move('./_upload/'.$oUpload->name());
         }
 }
?>
```
### Wiele plików i wiele elementów
```php
 <input type="file" name="image[]" />
 <input type="file" name="attachment[]" />
```
```php
<?php
 foreach (f_upload::each('image') as $oUpload) {
         if ($oUpload->is()) {
                 $oUpload->move('./_upload/'.$oUpload->name());
         }
 }
?>
```

                 lub jeśli chcemy jednocześnie przetworzyć wiele elementów input np.:
```php

 <input type="file" name="image_0" />


 <input type="file" name="image_1" />
```
```php
<?php
 foreach (f_upload::each() as $oUpload) {
         if ($oUpload->is()) {
                 $oUpload->move('./_upload/'.$oUpload->name());
         }
 }
?>
```
### Zaawansowany przykład użycia
                 html:
```
                 php:
```php
<?php
 foreach (f_upload::each() as $oUpload) {
         if ($oUpload->is()) {
                 $oImg = new m_img();
                 $oImg->img_id_user = $this->user->user_id;
                 $oImg->img_name    = $oUpload->name();
                 $oImg->img_size    = $oUpload->size();
                 $oImg->img_width   = $oUpload->imageWidth();
                 $oImg->img_height  = $oUpload->imageHeight();
                 $oImg->save();
                 $oImg->lastInsertId();

                 $oImage = new f_image();
                 $oImage->load($oUpload->tmpName());
                 $oImage->resize(640, 480)->saveAs('./_img/' . $oImg->img_id . '.jpg');
                 $oImage->thumb(80)->saveAs('./_img/s/' . $oImg->img_id . '.jpg');
                 $oImage->destroy();
         }
 }
?>

```


 [b][/b]