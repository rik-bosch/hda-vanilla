<?php
include('../../../src/UserIndex.php');

$data = json_decode(file_get_contents('../../../data/MOCK_DATA.json'), true);
$userIndex = new UserIndex($data, $_GET);
?>

<ul class="pagination">
    <?php foreach ($userIndex->getLinks() as $label => $link): ?>
        <li><a href="<?php echo $link['href'] ?>"><?php echo $label ?></a></li>
    <?php endforeach ?>
</ul>

<hr>

<div class="alterations">
    <p>Offset: <?php echo $userIndex->getOffset() ?></p>
    <label for="set-limit">Limit</label>
    <input id="set-limit"
            type="number"
            value="<?php echo $userIndex->getLimit() ?>"
            data-change-param="limit"
    >
</div>

<hr>

<table>
    <thead>
        <tr>
            <?php foreach(array_keys($data[0]) as $key): ?>
                <th><a href="<?php echo $userIndex->getUrl() . '?sortBy=' . $key ?>"><?php echo $key ?></a></th>
            <?php endforeach ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($userIndex->getData() as $row): ?>
            <tr>
                <?php foreach ($row as $val): ?>
                    <td><?php echo $val ?></td>
                <?php endforeach ?>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
