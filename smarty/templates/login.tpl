{include file="header.tpl"}
<div class="row mt-5">
    <div class="col-4 offset-4">
        <h1 class="text-center mb-3">Zaloguj się</h1>
        <form action="index.php" method="post">
            <input type="hidden" name="action" value="login">
            <label class="form-label" for="emailInput">Adres e-mail:</label>
            <input class="form-control mb-3" type="email" name="email" id="emailInput" required>
            <label class="form-label" for="passwordInput">Hasło:</label>
            <input class="form-control mb-3" type="password" name="password" id="passwordInput">
            <button class="btn btn-primary w-100" type="submit">Zaloguj</button>
        </form>
    </div>
</div>
{include file="footer.tpl"}