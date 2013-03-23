<blockquote>
      使用DiliCMS 必须满足以下要求:
</blockquote>

<ul>
    <?php foreach ($environments as $env): ?>
    <li>
        <div class="control-group <?php echo $env['status'] ? 'success' : 'error'; ?>">
          <div class="controls">
            <?php echo $env['name']; ?>
            <span class="help-inline pull-right"><strong><?php echo $env['status'] ? '满足' : '不满足'; ?></strong></span>
          </div>
        </div>
    </li>
    <?php endforeach; ?>
</ul>
