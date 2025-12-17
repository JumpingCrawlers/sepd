<select name="{{ $nombre_select }}" id="{{ $nombre_select }}" class="form-control @if (isset($errors)) {{ $errors->has($nombre_select) ? ' is-invalid' : '' }} @endif">
    <option value="">Provincia...</option> 
    <option value="001_Araba/Álava" title="Araba/Álava"{{ old('provincia', $valor_defecto)=='001_Araba/Álava' ? ' selected="selected"' : '' }}>Araba/Álava</option> 
    <option value="002_Albacete" title="Albacete"{{ old('provincia', $valor_defecto)=='002_Albacete' ? ' selected="selected"' : '' }}>Albacete</option> 
    <option value="003_Alicante/Alacant" title="Alicante/Alacant"{{ old('provincia', $valor_defecto)=='003_Alicante/Alacant' ? ' selected="selected"' : '' }}>Alicante/Alacant</option> 
    <option value="004_Almería" title="Almería"{{ old('provincia', $valor_defecto)=='004_Almería' ? ' selected="selected"' : '' }}>Almería</option> 
    <option value="005_Ávila" title="Ávila"{{ old('provincia', $valor_defecto)=='005_Ávila' ? ' selected="selected"' : '' }}>Ávila</option> 
    <option value="006_Badajoz" title="Badajoz"{{ old('provincia', $valor_defecto)=='006_Badajoz' ? ' selected="selected"' : '' }}>Badajoz</option> 
    <option value="007_Illes Balears" title="Illes Balears"{{ old('provincia', $valor_defecto)=='007_Illes Balears' ? ' selected="selected"' : '' }}>Illes Balears</option> 
    <option value="008_Barcelona" title="Barcelona"{{ old('provincia', $valor_defecto)=='008_Barcelona' ? ' selected="selected"' : '' }}>Barcelona</option> 
    <option value="009_Burgos" title="Burgos"{{ old('provincia', $valor_defecto)=='009_Burgos' ? ' selected="selected"' : '' }}>Burgos</option> 
    <option value="010_Cáceres" title="Cáceres"{{ old('provincia', $valor_defecto)=='010_Cáceres' ? ' selected="selected"' : '' }}>Cáceres</option> 
    <option value="011_Cádiz" title="Cádiz"{{ old('provincia', $valor_defecto)=='011_Cádiz' ? ' selected="selected"' : '' }}>Cádiz</option> 
    <option value="012_Castellón/Castelló" title="Castellón/Castelló"{{ old('provincia', $valor_defecto)=='012_Castellón/Castelló' ? ' selected="selected"' : '' }}>Castellón/Castelló</option> 
    <option value="013_Ciudad Real" title="Ciudad Real"{{ old('provincia', $valor_defecto)=='013_Ciudad Real' ? ' selected="selected"' : '' }}>Ciudad Real</option> 
    <option value="014_Córdoba" title="Córdoba"{{ old('provincia', $valor_defecto)=='014_Córdoba' ? ' selected="selected"' : '' }}>Córdoba</option> 
    <option value="015_A Coruña" title="A Coruña"{{ old('provincia', $valor_defecto)=='015_A Coruña' ? ' selected="selected"' : '' }}>A Coruña</option> 
    <option value="016_Cuenca" title="Cuenca"{{ old('provincia', $valor_defecto)=='016_Cuenca' ? ' selected="selected"' : '' }}>Cuenca</option> 
    <option value="017_Girona" title="Girona"{{ old('provincia', $valor_defecto)=='017_Girona' ? ' selected="selected"' : '' }}>Girona</option> 
    <option value="018_Granada" title="Granada"{{ old('provincia', $valor_defecto)=='018_Granada' ? ' selected="selected"' : '' }}>Granada</option> 
    <option value="019_Guadalajara" title="Guadalajara"{{ old('provincia', $valor_defecto)=='019_Guadalajara' ? ' selected="selected"' : '' }}>Guadalajara</option> 
    <option value="020_Gipuzkoa" title="Gipuzkoa"{{ old('provincia', $valor_defecto)=='020_Gipuzkoa"' ? ' selected="selected"' : '' }}>Gipuzkoa</option> 
    <option value="021_Huelva" title="Huelva"{{ old('provincia', $valor_defecto)=='021_Huelva' ? ' selected="selected"' : '' }}>Huelva</option> 
    <option value="022_Huesca" title="Huesca"{{ old('provincia', $valor_defecto)=='022_Huesca' ? ' selected="selected"' : '' }}>Huesca</option> 
    <option value="023_Jaén" title="Jaén"{{ old('provincia', $valor_defecto)=='023_Jaén' ? ' selected="selected"' : '' }}>Jaén</option> 
    <option value="024_León" title="León"{{ old('provincia', $valor_defecto)=='024_León' ? ' selected="selected"' : '' }}>León</option> 
    <option value="025_Lleida" title="Lleida"{{ old('provincia', $valor_defecto)=='025_Lleida' ? ' selected="selected"' : '' }}>Lleida</option> 
    <option value="026_La Rioja" title="La Rioja"{{ old('provincia', $valor_defecto)=='026_La Rioja' ? ' selected="selected"' : '' }}>La Rioja</option> 
    <option value="027_Lugo" title="Lugo"{{ old('provincia', $valor_defecto)=='027_Lugo' ? ' selected="selected"' : '' }}>Lugo</option> 
    <option value="028_Madrid" title="Madrid"{{ old('provincia', $valor_defecto)=='028_Madrid' ? ' selected="selected"' : '' }}>Madrid</option> 
    <option value="029_Málaga" title="Málaga"{{ old('provincia', $valor_defecto)=='029_Málaga' ? ' selected="selected"' : '' }}>Málaga</option> 
    <option value="030_Murcia" title="Murcia"{{ old('provincia', $valor_defecto)=='030_Murcia' ? ' selected="selected"' : '' }}>Murcia</option> 
    <option value="031_Navarra" title="Navarra"{{ old('provincia', $valor_defecto)=='031_Navarra' ? ' selected="selected"' : '' }}>Navarra</option> 
    <option value="032_Ourense" title="Ourense"{{ old('provincia', $valor_defecto)=='032_Ourense' ? ' selected="selected"' : '' }}>Ourense</option> 
    <option value="033_Asturias" title="Asturias"{{ old('provincia', $valor_defecto)=='033_Asturias' ? ' selected="selected"' : '' }}>Asturias</option> 
    <option value="034_Palencia" title="Palencia"{{ old('provincia', $valor_defecto)=='034_Palencia' ? ' selected="selected"' : '' }}>Palencia</option> 
    <option value="035_Las Palmas" title="Las Palmas"{{ old('provincia', $valor_defecto)=='035_Las Palmas' ? ' selected="selected"' : '' }}>Las Palmas</option> 
    <option value="036_Pontevedra" title="Pontevedra"{{ old('provincia', $valor_defecto)=='036_Pontevedra' ? ' selected="selected"' : '' }}>Pontevedra</option> 
    <option value="037_Salamanca" title="Salamanca"{{ old('provincia', $valor_defecto)=='037_Salamanca' ? ' selected="selected"' : '' }}>Salamanca</option> 
    <option value="038_Santa Cruz de Tenerife" title="Santa Cruz de Tenerife"{{ old('provincia', $valor_defecto)=='038_Santa Cruz de Tenerife' ? ' selected="selected"' : '' }}>Santa Cruz de Tenerife</option> 
    <option value="039_Cantabria" title="Cantabria"{{ old('provincia', $valor_defecto)=='039_Cantabria' ? ' selected="selected"' : '' }}>Cantabria</option> 
    <option value="040_Segovia" title="Segovia"{{ old('provincia', $valor_defecto)=='040_Segovia' ? ' selected="selected"' : '' }}>Segovia</option> 
    <option value="041_Sevilla" title="Sevilla"{{ old('provincia', $valor_defecto)=='041_Sevilla' ? ' selected="selected"' : '' }}>Sevilla</option> 
    <option value="042_Soria" title="Soria"{{ old('provincia', $valor_defecto)=='042_Soria' ? ' selected="selected"' : '' }}>Soria</option> 
    <option value="043_Tarragona" title="Tarragona"{{ old('provincia', $valor_defecto)=='043_Tarragona' ? ' selected="selected"' : '' }}>Tarragona</option> 
    <option value="044_Teruel" title="Teruel"{{ old('provincia', $valor_defecto)=='044_Teruel' ? ' selected="selected"' : '' }}>Teruel</option> 
    <option value="045_Toledo" title="Toledo"{{ old('provincia', $valor_defecto)=='045_Toledo' ? ' selected="selected"' : '' }}>Toledo</option> 
    <option value="046_Valencia/València" title="Valencia/València"{{ old('provincia', $valor_defecto)=='046_Valencia/València' ? ' selected="selected"' : '' }}>Valencia/València</option> 
    <option value="047_Valladolid" title="Valladolid"{{ old('provincia', $valor_defecto)=='047_Valladolid' ? ' selected="selected"' : '' }}>Valladolid</option> 
    <option value="048_Bizkaia" title="Bizkaia"{{ old('provincia', $valor_defecto)=='048_Bizkaia' ? ' selected="selected"' : '' }}>Bizkaia</option> 
    <option value="049_Zamora" title="Zamora"{{ old('provincia', $valor_defecto)=='049_Zamora' ? ' selected="selected"' : '' }}>Zamora</option> 
    <option value="050_Zaragoza" title="Zaragoza"{{ old('provincia', $valor_defecto)=='050_Zaragoza' ? ' selected="selected"' : '' }}>Zaragoza</option> 
    <option value="051_Ceuta" title="Ceuta"{{ old('provincia', $valor_defecto)=='051_Ceuta' ? ' selected="selected"' : '' }}>Ceuta</option> 
    <option value="052_Melilla" title="Melilla"{{ old('provincia', $valor_defecto)=='052_Melilla' ? ' selected="selected"' : '' }}>Melilla</option> 
    <option value="000" title="Otros"{{ old('provincia', $valor_defecto)=='000' ? ' selected="selected"' : '' }}>Otros</option>
</select>