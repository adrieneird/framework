<label for="<?= $name ?>"><?= ucfirst($name) ?></label>
<input
    type="password"
    name="<?= $name ?>"
    id="<?= $name ?>"
    value="<?= htmlspecialchars($value ?? '') ?>"
>