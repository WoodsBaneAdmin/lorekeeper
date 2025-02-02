@extends('layouts.app')
@section('title')
Breeding Roller
@endsection

@section('content')
<head>
    <title>Breeding Roller</title>
</head>
<body>
    <h1>Parent Traits</h1>
    <form method="POST" action="{{ route('roll') }}">
    @csrf {{-- Important: Add CSRF token for Laravel forms --}}

    <h2>Parent 1</h2>
    <section>
        <label for="geno1">Genotype:</label>
        <input type="text" id="geno1" name="geno1" value="{{ old('geno1') }}"> {{-- Use old() for previous input --}}

        <label for="type1">Type:</label>
        <select id="type1" name="type1">
        <option value="wyvern" @if (old('type1') == "wyvern") selected @endif>Wyvern</option>
        <option value="drake" @if (old('type1') == "drake") selected @endif>Drake</option>
        <option value="true dragon" @if (old('type1') == "true dragon") selected @endif>True Dragon</option>
        </select>

        <label for="element1">Element:</label>
        <select id="element1" name="element1">
        <option value="fire" @if (old('element1') == "fire") selected @endif>Fire</option>
        <option value="water" @if (old('element1') == "water") selected @endif>Water</option>
        <option value="air" @if (old('element1') == "air") selected @endif>Air</option>
        <option value="earth" @if (old('element1') == "earth") selected @endif>Earth</option>
        </select>

        <label for="elementTwo1">Second Element:</label>
        <select id="elementTwo1" name="elementTwo1">
        <option value="none" @if (old('elementTwo1') == "none") selected @endif>N/A</option>
        <option value="fire" @if (old('element1') == "fire") selected @endif>Fire</option>
        <option value="water" @if (old('element1') == "water") selected @endif>Water</option>
        <option value="air" @if (old('element1') == "air") selected @endif>Air</option>
        <option value="earth" @if (old('element1') == "earth") selected @endif>Earth</option>
        </select>
        <br>
    </section>

    <h2>Parent 2</h2>
    <section>
        <label for="geno2">Genotype:</label>
        <input type="text" id="geno2" name="geno2" value="{{ old('geno2') }}"> {{-- Use old() for previous input --}}

        <label for="type2">Type:</label>
        <select id="type2" name="type2">
        <option value="wyvern" @if (old('type2') == "wyvern") selected @endif>Wyvern</option>
        <option value="drake" @if (old('type2') == "drake") selected @endif>Drake</option>
        <option value="true dragon" @if (old('type2') == "true dragon") selected @endif>True Dragon</option>
        </select>

        <label for="element2">Element:</label>
        <select id="element2" name="element2">
        <option value="fire" @if (old('element2') == "fire") selected @endif>Fire</option>
        <option value="water" @if (old('element2') == "water") selected @endif>Water</option>
        <option value="air" @if (old('element2') == "air") selected @endif>Air</option>
        <option value="earth" @if (old('element2') == "earth") selected @endif>Earth</option>
        </select>

        <label for="elementTwo2">Second Element:</label>
        <select id="elementTwo2" name="elementTwo2">
        <option value="none" @if (old('elementTwo2') == "none") selected @endif>N/A</option>
        <option value="fire" @if (old('element2') == "fire") selected @endif>Fire</option>
        <option value="water" @if (old('element2') == "water") selected @endif>Water</option>
        <option value="air" @if (old('element2') == "air") selected @endif>Air</option>
        <option value="earth" @if (old('element2') == "earth") selected @endif>Earth</option>
        </select>
        <br>
    </section>

    <input type="submit" value="Submit">
    </form>

    @if (isset($error))
    <p style="color: red;">{{ $error }}</p>
    @endif

    
    @if (isset($outputs))
        <p><b>Total Number of Children: {{ $clutch }}</b></p> 
        @foreach ($outputs as $output)
            <p><b>Child Number: </b>{{ $output['number'] }}</p> 
            <p><b>Phenotype: </b>{{ $output['phenotype'] }}</p> 
            <p><b>Genotype: </b>{{ $output['genotype'] }}</p> 
            <p><b>Drakovai Type: </b>{{ $output['type'] }}</p> 
            <p><b>Drakovai Traits</b></p> 
            <ul>
                @foreach ($output['traits'] as $trait => $value)
                    <li><b>{{ $trait }}:</b> {{ $value }}</li>
                @endforeach
            </ul>
            <hr> 
        @endforeach
    @endif

</body>
@endsection
