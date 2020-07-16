<?php declare(strict_types=1);
ini_set('display_errors','1');
ini_set('display_startup_errors','1');
error_reporting(E_ALL);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- CSS only -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <link href="favicon.ico" rel="icon" type="image/x-icon" />


    <title>Pokedex</title>
</head>
<body>

<h1 class="p-5">Pokédex</h1>
<div id="pokedex" class="container">
    <div class="row">
        <div class="pokedex-left col-md-6">
            <div class="row state-icons">
                <div id="blue-state-icon"></div>
                <div id="red-state-icon"></div>
                <div id="yellow-state-icon"></div>
                <div id="green-state-icon"></div>
            </div>
            <div class="left-screen-border">
                <div class="left-inner-screen">
                        <?php
                            if(!empty ($_GET['search-input'])){
                                sleep(1);
                                $getpokeimg = file_get_contents('https://pokeapi.co/api/v2/pokemon/' . strtolower($_GET['search-input']));
                                $data = json_decode($getpokeimg, true);
                                $pokemonImg = $data['sprites']['front_default'];
                                echo '<img src="'.$pokemonImg.'" class="mx-auto">';
                            }

                        ?>
                </div>
            </div>
            <div class="input-group mt-5 mx-auto searchBar">
                <!-- <div> -->
                    <form action="" method="get" class="input-group-append">
                        <input type="text" name="search-input" id="search-input" class="px-3" placeholder="Pokémon name or ID"
                               aria-label="Pokémon name or ID" aria-describedby="basic-addon2">
                        <input type="submit" class="btn" id="searchPokemon" value="Search Pokémon" onclick="flickerAnimations()">
                    </form>
                <!-- </div> -->
            </div>
        </div>
        <div class="pokedex-right col-md-6">
            <div id="right-inner-screen">
                <?php
                if(!empty ($_GET['search-input'])){
                    $getpokemon = file_get_contents('https://pokeapi.co/api/v2/pokemon/' . strtolower($_GET['search-input']));
                    $data = json_decode($getpokemon, true);
                    $pokemonName = $data['name'];
                    $printPokeName = '<h1 id="pokeName" class="text-center">Name: '.ucfirst($pokemonName).'</h1>';
                    echo $printPokeName;
                    $pokemonId = $data['id'];
                    $printPokeId = '<h2 id="pokeId" class="text-center">ID: #'.ucfirst(strval($pokemonId)).'</h2>';
                    echo $printPokeId;
                    $getPokeTypes = $data['types'];
                    $pokeTypesArr = [];
                    foreach($getPokeTypes AS $key => $pokeType){
                        array_push($pokeTypesArr, $pokeType['type']['name']);
                    }
                    echo '<h3 id="pokeTypes" class="text-center">Type: '.implode(", ", $pokeTypesArr).'</h3>';
                }
                if(isset ($_POST['moves-btn'])){
                    $getPokemonMoves = $data['moves'];
                    $pokeMovesArrSliced = array_slice($getPokemonMoves, 0, 4);
                    $pokeMovesArr = [];
                    foreach($pokeMovesArrSliced AS $key => $pokeMove){
                        array_push($pokeMovesArr, $pokeMove['move']['name']);
                    }
                    /* $html = preg_replace('#<h1 id="pokeName" class="text-center">(.*?)</h1>#', '', $printPokeName);
                    echo $html; */
                    echo '<h2 id="pokeMoves" class="text-center">Moves:</h2>';
                    echo '<p id="pokeMoves">'.implode(", ", $pokeMovesArr).'</p>';
                }
                if (isset ($_POST['prev-evol-btn'])) {
                    $getEvolution = file_get_contents('https://pokeapi.co/api/v2/pokemon-species/' . strtolower($_GET['search-input']));
                    $data = json_decode($getEvolution, true);
                    //var_dump($data);
                    $getEvolChainUrl = $data['evolution_chain']['url'];
                    $getEvolutionChainData = file_get_contents($getEvolChainUrl);
                    $evolutionChainData = json_decode($getEvolutionChainData, true);
                    //var_dump($evolutionChainData);
                    $evolChain = $evolutionChainData['chain'];
                    $evolvesTo = $evolChain['evolves_to'];
                    if (empty($evolvesTo)) {
                        echo "no previous or next evolution";
                    }
                    else if(count($evolvesTo) == 1){
                        if($evolChain['species']['name'] === strtolower($_GET['search-input'])){
                            echo "this is the starter pokemon of this breed";
                        }
                        else if($evolvesTo['0']['species']['name'] === strtolower($_GET['search-input'])){
                            echo "this is the first evolution of this breed";
                        }
                        else if($evolvesTo['0']['evolves_to']['0']['species']['name'] === strtolower($_GET['search-input'])){
                            echo "this is the second evolution of this breed";
                        }
                    }
                    else if(count($evolvesTo) > 1){
                        echo "there are multiple evolutions possible";
                    }

                    //var_dump($evolvesTo);
                }
                ?>
            </div>
            <form action="" method="post">
            <section class="buttons container mx-auto">
                <div class="row">
                        <input type="submit" id="info-btn" class="btn pokedex-btn" value="More info" onclick="flickerAnimations()">
                        <input type="submit" name="moves-btn" id="moves-btn" class="btn pokedex-btn" value="Moves" onclick="flickerAnimations()">
                        <input type="submit" name="prev-evol-btn" id="prev-evol-btn" class="btn pokedex-btn" value="Previous evolutions" onclick="flickerAnimations()">
                        <input type="submit" name="prev-evol-btn" id="next-evol-btn" class="btn pokedex-btn" value="Next evolutions" onclick="flickerAnimations()">
                </div>
            </section>
            </form>
        </div>
    </div>
</div>


<!-- JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
        crossorigin="anonymous"></script>
<script src="js/script.js"></script>
</body>
</html>