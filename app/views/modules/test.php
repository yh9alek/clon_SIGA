<div class="d-flex justify-content-center align-items-center" 
    style="position: absolute; left: calc(50vw - 120px); top: calc(50vh - 50px);">

    <img src="/assets/src/imgs/php-logo.svg" alt="PHP" width="120px">
    <h1 class="text-center" style="font-size: 32px;">
        -
        BA<span style="color: #EE5050;">YH9</span>
    </h1>
    <ul class="productList">
        <?php foreach($products as $product): ?>
            <li class="item">
                <?= $product['img'] ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
