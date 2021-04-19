{include file="header.tpl"}
<div class="row mt-5">
    <div class="col-4 offset-4">
        <h1>Witamy ponownie przyjacielu !</h1>
        <p>Twoje zajebiste id: {$id}
        </p>
        <p>Twój email: {$email}
        </p>
        <form action="index.php" method="post">
            <input type="hidden" name="action" value="logout">
            <button type="submit" class="btn btn-primary w-100">Wywalaj z konta</button>
        </form>

    </div>
</div>
{include file="footer.tpl"}