<blockquote>
  <p>DiliCMS 目前支持普通环境以及SAE(新浪云平台).</p>
</blockquote>
<?php if ( ! is_sae()): ?>
    <div class="alert">
        当前运行环境为: <strong>普通环境</strong> 
    </div>
    <?php if ( ! $is_config_ok): ?>
    <div class="alert alert-error">
        shared/config/platform.php <strong>未正确配置</strong>，请先正确配置shared/config/platform.php。<br />
        shared/config/platform.php必须可读且type的值必须为default.
    </div>
    <?php else: ?>
    <div class="alert">
        shared/config/platform.php <strong>配置正确</strong>
    </div>
    <?php endif; ?>
<?php else: ?>
    <div class="alert">
        当前运行环境为: <strong>SAE</strong>, 需要开启以下环境: 
        <ul>
            <li>Memcache</li>
            <li>MySQL</li>
            <li>Storage, 请建立domain名为public的Storage，并且配置shared/config/platform.php</li>
        </ul>
    </div>
    <?php if ( ! $is_memcache_ok): ?>
    <div class="alert alert-error">
        Memcache <strong>未开启</strong>，请到SAE控制面板开启。 
    </div>
    <?php else: ?>
    <div class="alert">
        Memcache <strong>已开启</strong>
    </div>
    <?php endif; ?>
    <?php if ( ! $is_mysql_ok): ?>
    <div class="alert alert-error">
        MySQL <strong>未初始化</strong>，请到SAE控制面板初始化。 
    </div>
    <?php else: ?>
    <div class="alert">
        MySQL <strong>已初始化</strong>
    </div>
    <?php endif; ?>
    <?php if ( ! $is_storage_ok): ?>
    <div class="alert alert-error">
        Storage <strong>未建立</strong>，请到SAE控制面板建立名为public的Storage。 
    </div>
    <?php else: ?>
    <div class="alert">
        Storage <strong>已建立</strong>
    </div>
    <?php endif; ?>
<?php endif; ?>