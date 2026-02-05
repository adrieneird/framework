<label for="<?= Response::format($name) ?>"><?= ucfirst(Response::format($name)) ?></label>
<input
    type="password"
    name="<?= Response::format($name) ?>"
    id="<?= Response::format($name) ?>"
    autocomplete="current-password"
>