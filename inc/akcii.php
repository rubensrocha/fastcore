<div class="card mb-2">
    <div class="p-2">
        <h5 class="text-uppercase mb-0 text-center"><b>Внимание акция!!!</b> Пополни баланс и получи бонус!</h5>
        <div class="nav nav-pills nav-fill text-uppercase">
            <?php
            $bon_p = $db->query('SELECT * FROM db_percent WHERE type = 1  ORDER BY sum_a < sum_a DESC LIMIT 7')->fetchAll();
            foreach ($bon_p as $bon_p) {
                ?>
                <div class="nav-item nav-link alert-warning text-dark m-1 p-1 text-center">
                    <div style="padding: 5px 2px;font-size: 90%;">ПОПОЛНИ<br/>
                        от <b class="text-danger"><?= $bon_p['sum_a'] ?></b> до <b
                            class="text-danger"><?= $bon_p['sum_b'] ?></b> руб. <br/> <b><span
                                class="text-white bg-danger" style="padding: 3px 5px; border-radius: 1em;">бонус <span
                                    style="color: #ffef02;">+<?= $bon_p['sum_x'] * 100 ?>%</span></span></b></div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>