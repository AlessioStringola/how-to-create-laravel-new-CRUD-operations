<?php 
// Per clonare un laravel facciamo:
// -git clone ssh-key.
// -composer update.
// -cp .env.example .env
// -php artisan key:generate

// Per creare un laravel new lanciamo il comando sul terminale.
// -Poi installiamo le librerie npm i, npm i bootstrap
// -Creaiamo i file style.css e main.js
// -Creiamo la cartella components con all' interno il layout e la navbar, all'interno del layout lanciamo @vite nella head inseriamo la navabar e lo slot. Lanciamo npm run dev
// -Ora creiamo la prima rotta per la vista della home. ANdiamo in web.php creiamo la rotta ed un controller generico(per esempio PublicController) e all'interno di questo controller inseriamo la return view di home nella public function home, poi creiamo il file home.blade nella view e cancelliamo il welcome, inseriamo il layout e sul progetto si vedrà la nostra pagina home all' uri /.
// -Ora dobbiamo creare il form per la creazione dei record
// -Creiamo il modello con cui laravel si interfaccerà con i record del database, se lanciamo il comando php artisan make:model Game -mcr, artisan creerà il modello Game, la migrazione create_games_table ed il controller GameController con all' interno tutte le public function che serviranno per le CRUD operations.
// -La prima operazione CRUD che faremo sarà la create che deve ritornare la vista del form dello store
// -La seconda operazione è lo store in cui richiameremo il modello::create([]) e nell' array associativo inseriamo i campi da cui sarà composto il record.
// -Ora creiamo il form nella pagina blade create, all'interno della cartella game.
// -Ora andiamo a completare la tabella nella migrazione
// -Una volta completata la tabella andiamo nel .env nella sezione relativa al database e inseriamo il tipo di gestore(nel nostro caso mysql), la password root e il nome del database, poi lanciamo nel terminale il comando php artisan migrate. In questo caso artisan ci chiederà se vogliamo creare il database con il nome che abbiamo inserito nel .env, e poi completerà la migrazione.
// -Ora creiamo la sottocartella images nella public dello storage, inseriamo una immagine di default e creiamo il collegamento tra le cartelle public con php artisan storage:link
// -Ora dobbiamo creare le validazioni per il form della create. Inseriamo la injection nella request e creiamo appunto la nuova request che sarà GameStoreRequest, entriamoci e mettiamo l'autorizzazione true ed inseriamo le validazioni

// Arrivati a questo punto il database viene creato regolarmente senza problemi

// -Ora dobbiamo creare una pagina index per l'elenco dei giochi ed una pagina show per la vista dettaglio del singolo gioco, cioè la reading della CRUD.

// -Scriviamo nel web.php le due rotte index e show,(la rotta show sarà una rotta parametrica dove inseriremo nell' uri nelle graffe tutto l'oggetto game, cosi facendo verrà effettuato un find automatico nella funzione show grazie all' injection Game)
// -Nel controller scriviamo le due funzioni, nell' index inizializzeremo una variabile $games=Game::all(); cosi avremo tutti i giochi e ritorniamo la vista compattata di game.index, al solito creiamo la pagina blade nella cartella game. Nello show ritorniamo la vista di game.show compattata('game') e creiamo lo show nella cartella game.
// -Ora creiamo le due rotte per la modifica edit e update.
// -Nel controller la funzione edit ritorna una vista di game.edit compattata, la funzione update ha bisogno dell' oggetto $game creato nel parametro della funzione stessa(Game $game), quindi all' interno usiamo l' oggetto $game con una sua funzione interna update, quindi $game->update() e all' interno creiamo l' array associativo con le stesse chiavi della create per modificare il record con la nuova request.
// -Creiamo nella cartella game un file edit.blade e all' interno di questo file inseriamo il form della create, modifichiamo l' action con game.update,compact('game') modifichiamo il method che rimarrà post ma va aggiunto dopo il token @method('put), infine modifichiamo il valore di value nei campi del form, non avremo più la funzione old(), ma il reale valore che quei record hanno, ad esempio nella description all' interno del contenuto della textarea mettiamo{{$game->description}}.
// -Modifichiamo quindi anche l'index dei giochi aggiungendo un bottone game update e inseriamoci nell' href la rotta parametrica dell' edit.
// -Per completare le CRUD operation inseriamo la rotta parametrica delete, nel controller usiamo il metodo $game->delete();, inseriamo nell' index il pulsante destroy, mediante l' utilizzo di un form.

// -Il middleware lo possiamo utilizzare se abbiamo installato il pacchetto fortify, ed è una sorta di discriminante nel senso che permette l'accesso oppure lo nega alle funzioni, infatti il middleware è presente nel controller. Essendo il controller una classe possiamo dichiarare il costruttore e scrivere all'interno $this->middleware(). Noi possiamo avere diversi middleware nel nostro progetto, sia scritti da noi sia tramite altri pacchetti. Quello che utilizzeremo noi è quello dell' autenticazione($this->middleware('auth');) fornito dal pacchetto fortify
// -Ora supponiamo di volere categorizzare i nostri game, essi possono appartenere solo ad una categoria, quindi hanno una relazione 1 ad N.
// -Tutte le categorie hanno un id che chiameremo category_id, basterà dichiararle nella migrazione, nella function up() e avremo un array di categorie.
// -Ora dobbiamo spiegare questa relazione al database e a laravel.
// -Lanciamo la migrazione php artisan make:migration create_categories_table, poi lanciamo php artisan make:migration add_category_id_column_to_games_table
// -Nella migrazione up scriviamo $categories=[]; foreach($categories as $category){Category::create(["name"=>$category])}; creiamo il nuovo modello Category, aggiungiamo il protected $fillable.
// -Ora passiamo all' altra migrazione, nella up creiamo una unsignedBigInteger('category_id')->nullable(), $table->foreign('category_id')->references('id')->on('categories');  e nella down($table->dropForeign('category_id');
 // $table->dropColumn('category_id');).
//  -Lanciamo php artisan migrate. 
//  -Ora a livello di database la relazione è stata spiegata.
//  -Ora dobbiamo spiegare la relazione dal punto di vista di laravel. Andiamo nel modello Category e Game.
// Nel Game inseriamo  public function category(){
    //     return $this->belongsTo(Category::class);
    // }
// Nella Category public function games(){
    //     return $this->hasMany(Game::class);
    // }
// -Ora dobbiamo dare la possibilità all' utente di selezionare una categoria, nel web.php abbiamo già la rotta, andiamo nel GameController nella funzione create aggiungiamo $categories=Category::all() e compattiamo la return aggiungiamo una select nel form del create e modifichiamo l' array associativo della funzione store .
// -Ora aggiungiamo la validazione e completiamo il protected $fillable nel modello, poi aggiungiamo nella pagina index, nella card la categoria a cui appartiene quel record creato, aggiungendo $game->category->name.
// -Ora se vogliamo possiamo aggiungere una filterbycategory, vedendo la relazione da un punto di vista della category. Creiamo nel web.php la rotta get filterbycategory, nelGameController scriviamo la public function con il Category $category come parametro, ritorniamo la vista di game.filterbycategory compattata in category. Nell' index aggiungiamo l' anchore con route('game.filterbycategory,["category"]=>$game->category') e nel contenuto $games->category->name.
// -Cosi facendo vedremo nell' anchore il nome della categoria per la quale vogliamo filtrare i record esistenti.
// -Creiamo ora la pagina filterbycategory.blade nella cartella game, inseriamo un il titolo della category, cioè {{$category->name}} facciamo un foreach($category->games as $game) e copiamo il foreach dell' index.
// -Ora avremo una pagina dedicata per ogni categoria con all' interno la lista dei record appartenenti ad essa.

// SEZIONE FORTIFY
// -Ora dobbiamo usare l' autenticazione, per fare questo useremo il pacchetto fortify.
// -Lanciamo composer require laravel/fortify
// -Lanciamo php artisan fortify:install
// -Lanciamo php artisan migrate
// -modifichiamo i provider(FortifyServiceProvider) con la rotta login e register
// -Creiamo la cartella auth e i due file login e register che sono due form, per capire come farli guardiamo la documentazione laravel.
// -Nel config. fortify.php gestiamo la redirect
// -Nella navbar implementiamo le tre rotte route('register), route('login), poi creiamo un form con action{{route('logout)}} dove all' interno creiamo un bottone Logout.
// -Gestiamo i bottoni con le direttive @guest, @auth, @else

// SEZIONE MAIL
// -Ora vogliamo fare in modo di poter essere contattati dall' utente.
// -Creiamo le rotte per la mail
// -Creiamo la pagina blade nella view e creiamo il form
// -Ora nella public function sendEmail(Request $request) nel PublicController avremo i dati che arrivano dal form
// -Ora lanciamo il comando php artisan make:mail ConfirmationEmail, ora abbiamo una nuova classe php che ci consente di gestire i dati del form.
// -All' interno della cartella mail appena creata avremo il ConfirmationEmail, all' interno del quale avremo una serie di funzioni, tra le quali Envelope. In questa funzione abbiamo il subject: 'Confirmation Email from db', e il from:new Address('admin@mail.com','admin'). Nella funzione content facciamo return 'mail.confirmation'. Quindi ora creiamo una cartella mail e un file confirmation.blade. All' interno di questo blade ci sarà la pagina mail che l' utente visualizzerà, inizializziamo con html:5 e scriviamo grazie per averci contattato.
// -Ora nel PublicController settiamo la function sendEmail scrivendo all' interno Mail::to($request->email)->send(new ConfirmationEmail())
// -Usiamo mailtrap per testare la email, andiamo in my-inbox nei settings e nelle integrations selezioniamo laravel 9+, copiamo i dati relativi alla email e li ricopiamo nel .env nella sezione delle mail.
// -Ora vediamo nella inbox che il messaggio del blade confirmation viene visualizzato
// -Se andiamo nel ConfirmationEmail ed includiamo nella classe un public $body e lo richiamiamo nel costruttore, potremo utilizzarlo nel parametro della new ConfirmationEmail nella funzione sendEmail nel PublicController, in questo modo possiamo ottenere quello che ci viene scritto dall' utente.
