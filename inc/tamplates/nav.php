<div class="flex justify-between my-5 text-xl">
  <div>
    <a href="index.php?task=report" class="text-[orangered] hover:text-black mx-3">All Singers</a>
    <?php if (isAdmin() || isEditor()): ?> |
      <a href="index.php?task=add" class="text-[orangered] hover:text-black mx-3">Add Singers</a>
    <?php endif; ?>
    <?php if (isAdmin()): ?> |
      <a href="index.php?task=seed" class="text-[orangered] hover:text-black mx-3">Seed</a>
    <?php endif; ?>
  </div>

  <div>
    <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
      <a href="simple_auth.php?logout=true" class="text-[orangered] hover:text-black mx-3">Log Out
        (<?php echo $_SESSION['role']; ?>)</a>
    <?php else: ?>
      <a href="simple_auth.php" class="text-[orangered] hover:text-black mx-3">Log In</a>
    <?php endif; ?>
  </div>
</div>