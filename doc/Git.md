# Git


## Instalacja

- Linux `$ sudo apt-get install git-core`
- Mac `$ sudo apt-get install git-core`
- Windows http://code.google.com/p/msysgit


## Wstep

1. Katalog roboczy - pliki projektu
2. Przechowalnia - stan pomiedzy katalogiem roboczym a katalogiem git
3. Katalog git - baza repozytorium

Pliki dodawane sa z katalogu roboczego do przechowalni - tworzona jest tzw. migawka.

Z przechowalni dokonywany jest commit do repozytorium.

Przechowalnia moze byc pominieta.

http://progit.org/book/pl/


## Konfiguracja

Tozsamosc uzywtkonika

`$ git config --global user.name "Jan Nowak"`

`$ git config --global user.email jannowak@example.com`

Kolory w konsoli

`$ git config --global color.ui auto`


## Podstawy

Utworzenie repozytorium

``` 
$ mkdir project1
$ cd project1
$ git init
```

Pierwszy commit

```
$ echo "Hello World!" >> file.txt
$ git status
$ git add -A && git commit -m 'utworzenie file.txt'
```

`$ git status` - wyswietla status

`$ git add -A` - dodaje wszystkie nowe, zmodyfikowane, usuniete pliki do przechowalni.

`$ git commit -m 'opis...'` - dodaje pliki z przechowalni do bazy repozytorium


`;` oddziela polecenia i wykonuje je niezaleznie. 
`&&` wykonuje polecenia zaleznie, jesli w poprzednim nastapi blad to nastepne nie zostanie wykonane.


`$ git add -A && git commit -m 'opis...'` - "pominiecie przechowalni"


## Git Log

przegladanie historii commitow

`$ git log`

- `--stat` - zsumowane zmiany w plikach
- `-p` - dokladne roznice w plikach
- `--pretty=oneline` - jeden commit w jednej linii
- `--graph` - graf 
- `--max-count=5` - ostatnie 5 commitow

np. `git log --stat --max-count=5 --graph`

## Git Diff

1. `$ git diff` - roznica pomiedzy katalogiem roboczym i przechowalnia
2. `$ git diff --cached` - roznica pomiedzy przechowalnia i ostatnim commitem
3. `$ git diff HEAD` - roznica pomiedzy katalogiem roboczym i ostatnim commitem

`--stat` - zsumowane zmiany w plikach np.  `$ git diff --stat`

## Centralne repozytorium

Centralne repozytorium to `bare`. 
Takie repozytorium nie posiada katalogu roboczego i przechowalni. 


### Utworzenie centralnego repozytorium

```
$ cd /Volumes/httpdocs/vhosts/project1/ios/
$ mkdir project1.git
$ cd projeckt1.git
$ git init --bare --shared
$ chmod -R 777 ./
$ chmod -R -x hooks
$ git remote add origin /Volumes/httpdocs/vhosts/project1/ios/project1.git
```

`origin` to nazwa remote

### Lokalny klon

```
$ cd /Users/uzytkownik/Documents/
$ git clone /Volumes/httpdocs/vhosts/project1/ios/project1.git
```

### Dodanie danych do lokalnego repozytorium i wypchniecie zmian do centralnego

```
$ cd project1
$ cp -r sciezka-do-plikow-projektu/* .
$ git add -A && git commit -m 'inicjalizacja projektu'
$ git push origin
```

### Kolejny uzytkownik 

```
$ cd /Users/uzytkownik-kolejny/Documents/
$ git clone /Volumes/httpdocs/vhosts/project1/ios/project1.git
$ cd project1
$ git log
```

### Praca z zdalnym repozytorium

Nasze zmiany:

```
$ echo "nowa linia tekstu" >> file.txt
$ git add -A && git commit -m 'dodano nowa linie tekstu'
```

Przed wypchnieciem swoich zmian do centralnego repozytorium nalezy sciagnac do siebie aktualna wersje:

```
$ git pull
```

Zazwyczaj konflikty powinny zostać rozwiązane automatycznie (pliki są scalane).
Jeśli jest to niemożliwe to trzeba odpalić polecenie `git mergetool`:

```
$ git mergetool
// tutaj otworzy sie zewnetrzy program to scalania plikow
// po zakonczeniu scalania nalezy zamknac program scalania
$ git add -A && git commit -m 'rozwiazanie konfiktow w metodzie jakies tam'
```

Nastepnie wypychamy zmiany do centralnego repozytorium:

```
$ git push origin
```


## Ignorowanie plikow

plik `.gitignore`

```
.error
nbproject
*~
```

## Push do zewnetrznego repozytorium non-bare, do aktualnej galezi, do katalogu roboczego

Przypadek aktualizacji strony internetowej na serwerze produkcyjnym.

Standardowo nie jest mozliwy push do zewnetrzengo repozytorium, do aktualnej galezi.

W `.git/config` dodaj:
 
```
[receive]
    denyCurrentBranch = ignore
```
 
Utworz `.git/hooks/post-receive` z:

``` 
#!/bin/sh
cd .. && env -i git reset --hard
```
 
`$ chmod +x .git/hooks/post-receive`



