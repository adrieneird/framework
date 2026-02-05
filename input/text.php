<label for="<?= Response::format($name) ?>"><?= ucfirst(Response::format($name)) ?></label>
<input
    type="text"
    name="<?= Response::format($name) ?>"
    id="<?= Response::format($name) ?>"
    value="<?= Response::format($value) ?>"
>