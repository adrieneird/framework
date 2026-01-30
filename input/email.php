<label for="<?= $name ?>"><?= ucfirst($name) ?></label>
<input
    type="email"
    name="<?= $name ?>"
    id="<?= $name ?>"
    value="<?= htmlspecialchars($value ?? '') ?>"
>