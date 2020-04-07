<?php
$action = $_GET['action'] ?? 'index';

switch( $action )
{
    case "create":
        $brand = $_POST['brand'];
        $price = $_POST['price'];
        $count = (int)( $_POST['count'] );

        if( empty( $brand ) && empty( $price ) && empty( $count ) )
        {
            die( "Не додано" );
        }
        elseif( empty( $brand ) || empty( $price ) || empty( $count ) )
        {
            die( "Не всі поля заповнені" );
        }

        createEntity( $brand, $price, $count );
        break;

//    case "delete":
//        echo deleteAll();
//        echo getTable();
//        break;

    case "index":
    default:
        echo getTable();
}

function createEntity( string $brand, string $price, int $count )
{
    file_put_contents( "cars.txt", "brand=$brand,price=$price,count=$count;", FILE_APPEND );
    echo getTable();
}

//function deleteAll()
//{
////    $row_number = 0;    //номер строки которую удаляем
////    $file_out   = file( "cars.txt" ); // Считываем весь файл в массив
////
////    //записываем нужную строку  в файл
////    file_put_contents( "temp.txt", $file_out[ $row_number ], FILE_APPEND );
////
////    //echo "$file_out[$row_number]";
////    //удаляем записаную строчку
////    unset( $file_out[ $row_number ] );
////
////    //записали остачу в файл
////    file_put_contents( "cars.txt", implode( "", $file_out ) );
//}

function getTable(): string
{
    $html  =
        '<form action="index.php?action=create" method="POST">
            <input id="brand" type="text" name="brand" placeholder="Brand car">
            <input id="price" type="text" name="price" placeholder="Price">
            <input id="count" type="number" name="count" placeholder="Count">
            <input type="submit" value="SEND">
            <input type="reset" value="CLEAR">
        </form>';

    $html .= "<table border='1'>
        <thead>
            <th>Brand</th>
            <th>Price</th>
            <th>Count</th>
        </thead>
        <tbody>";

    foreach( getData() as $field => $value )
    {
        $html .=
            "<tr>" .
            "<td>{$value['brand']}</td>" .
            "<td>{$value['price']}</td>" .
            "<td>{$value['count']}</td>" .
            "</tr>";
    }

    $html .= "</tbody></table>";

    return $html;
}

function getData(): array
{
    $cars = [];

    $carStr = file_get_contents( "cars.txt" );

    if( !$carStr )
    {
        return $cars;
    }

    $car = explode( ";", $carStr );

    foreach( $car as $row )
    {
        if( !$row )
        {
            continue;
        }

        $tmp = explode( ",", $row );

        $r = [];
        foreach( $tmp as $t )
        {
            [ $key, $value ] = explode( "=", $t );
            $r[ $key ] = $value;
        }
        $cars[] = $r;
    }
    return $cars;
}

