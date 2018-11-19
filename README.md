# Show Products Search (Mostra prodotti Woocommerce per chiave di ricerca)
Plugin Wordpress estremamente semplice, aggiunge uno shortcode che permette di visualizzare i prodotti Woocommerce che fanno matching con una query di ricerca. Il sistema di ricerca utilizzato è quello base di Woocommerce/Wordpress. Il layout utilizzato è `woocommerce/content-product.php`, ovvero quello base di Woocommerce se non sovrascritto dal proprio template.

È possibile, per una gestione più oculata dei prodotti mostrati, specificare SKU di prodotti da includere ed escludere dalla ricerca, oltre a specificare il numero di risultati da recuperare.

## Parametri Shortcode
+ `nmb_items`: Numero di elementi da mostrare
+ `search`: Query di ricerca
+ `columns`: Numero di colonne, se non specificato è 4
+ `include_sku`: SKU dei prodotti da mostrare che non sono inclusi nei risultati per Query
+ `exclude_sku`: SKU dei prodotti da non mostrare ma che sono inclusi nei risultati per Query

## Esempi d'uso
+ Mostra i primi 5 risultati di ricerca per la query "Fumetto Marvel Avengers" mostrando un layout a 3 colonne:

```
[ShowProductsSearch 
  nmb_items="5"
  search="Fumetto Marvel Avengers" 
  columns="3" 
 ]
```

+ Mostra tutti i risultati di ricerca per la query "Fumetto Marvel" escludendo dal risultato i prodotti con SKU FUMETTOMARVEL1 e FUMETTOMARVEL2 e includendo i prodotti con SKU ACTIONFIGUR1, ACTIONFIGUR2:

```
[ShowProductsSearch 
  search="Fumetto Marvel Avengers" 
  exclude_sku="FUMETTOMARVEL1, FUMETTOMARVEL2" 
  include_sku="ACTIONFIGUR1, ACTIONFIGUR2"
]
```
