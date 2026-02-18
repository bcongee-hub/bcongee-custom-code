/**
 * 益粥 結帳欄位自訂 v2.2
 * 取代 Flexible Checkout Fields 外掛
 * 
 * ⚠️ 此程式碼與 WooMP（結帳好用版）完全相容
 *    不會影響 WooMP 的地址連動、離島判斷
 * 
 * 使用方式：貼到「程式碼片段」外掛，選擇「全域執行」
 * 
 * 功能：
 * 1. 移除多餘欄位（姓氏、公司、地址第二行）
 * 2. 修改標籤文字（更有溫度）
 * 3. 用 CSS flexbox 強制欄位排序（蓋過 WooMP）
 * 4.「帳單資訊」→「收件資訊」
 * 5. 隱藏「運送到不同的地址」區塊
 * 6. 信任元素
 * 7. 結帳頁 noindex
 * 
 * @version 2.2.0
 * @since   2026-02-18
 */


/**
 * ═══════════════════════════════════════════
 * 1. 移除多餘欄位 + 修改標籤 + 排序
 *    ※ priority 99 確保跑在 WooMP 之後
 * ═══════════════════════════════════════════
 */
add_filter( 'woocommerce_checkout_fields', 'bcongee_customize_checkout_fields', 99 );

function bcongee_customize_checkout_fields( $fields ) {

    // ── 移除不需要的欄位 ──
    unset( $fields['billing']['billing_last_name'] );
    unset( $fields['billing']['billing_company'] );
    unset( $fields['billing']['billing_address_2'] );

    // ── 修改標籤 ──
    if ( isset( $fields['billing']['billing_first_name'] ) ) {
        $fields['billing']['billing_first_name']['label']       = '收件人姓名';
        $fields['billing']['billing_first_name']['placeholder'] = '請輸入收件人全名';
        $fields['billing']['billing_first_name']['class']       = array( 'form-row-wide' );
    }

    if ( isset( $fields['billing']['billing_phone'] ) ) {
        $fields['billing']['billing_phone']['label'] = '聯絡電話';
    }

    if ( isset( $fields['billing']['billing_email'] ) ) {
        $fields['billing']['billing_email']['label']       = '訂購人 Email';
        $fields['billing']['billing_email']['description'] = '電子發票、出貨通知等皆寄送至此信箱';
    }

    if ( isset( $fields['billing']['billing_address_1'] ) ) {
        $fields['billing']['billing_address_1']['placeholder'] = '例如：中正路 100 號 3 樓';
    }

    // ── 重新排序 ──
    $priority_map = array(
        'billing_first_name' => 10,
        'billing_phone'      => 20,
        'billing_email'      => 30,
        'billing_postcode'   => 40,
        'billing_state'      => 50,
        'billing_city'       => 60,
        'billing_address_1'  => 70,
    );

    foreach ( $priority_map as $field_key => $priority ) {
        if ( isset( $fields['billing'][ $field_key ] ) ) {
            $fields['billing'][ $field_key ]['priority'] = $priority;
        }
    }

    // ── 自訂訂單備註 ──
    if ( isset( $fields['order']['order_comments'] ) ) {
        $fields['order']['order_comments']['label']       = '您的訂單備註:';
        $fields['order']['order_comments']['placeholder'] = '例如：假日不收件、7-11取貨與店名、管理員代收等';
    }

    return $fields;
}


/**
 * ═══════════════════════════════════════════
 * 2. 「帳單資訊」→「收件資訊」
 * ═══════════════════════════════════════════
 */
add_filter( 'gettext', 'bcongee_rename_billing_heading', 10, 3 );

function bcongee_rename_billing_heading( $translated, $text, $domain ) {
    if ( $domain === 'woocommerce' ) {
        if ( $text === 'Billing details' || $text === 'Billing Details' || $text === 'Billing &amp; Shipping' ) {
            return '收件資訊';
        }
    }
    return $translated;
}


/**
 * ═══════════════════════════════════════════
 * 3. CSS 強制排序 + 隱藏運送地址
 *    + JS 修正 placeholder（WooMP 會覆蓋）
 *    ※ 用 flexbox order 確保視覺順序正確
 * ═══════════════════════════════════════════
 */
add_action( 'wp_head', 'bcongee_checkout_css' );

function bcongee_checkout_css() {
    if ( ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
        return;
    }
    ?>
    <style>
        /* 隱藏「運送到不同的地址」整個區塊 */
        .woocommerce-shipping-fields {
            display: none !important;
        }

        /* 用 flexbox 強制欄位視覺排序（蓋過 WooMP） */
        .woocommerce-billing-fields__field-wrapper {
            display: flex !important;
            flex-direction: column !important;
        }
        #billing_first_name_field { order: 1 !important; }
        #billing_phone_field      { order: 2 !important; }
        #billing_email_field      { order: 3 !important; }
        #billing_postcode_field   { order: 4 !important; }
        #billing_state_field      { order: 5 !important; }
        #billing_city_field       { order: 6 !important; }
        #billing_address_1_field  { order: 7 !important; }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // WooMP 會在後面覆蓋 placeholder，所以用 JS 再改一次
        var addr = document.querySelector('#billing_address_1');
        if (addr) {
            addr.setAttribute('placeholder', '例如：中正路 100 號 3 樓');
        }
    });
    </script>
    <?php
}


/**
 * ═══════════════════════════════════════════
 * 4. 信任元素（付款方式下方）
 * ═══════════════════════════════════════════
 */
add_action( 'woocommerce_review_order_after_payment', 'bcongee_checkout_trust_badges' );

function bcongee_checkout_trust_badges() {
    ?>
    <div class="bcongee-trust-badges" style="
        text-align: center;
        padding: 16px 12px;
        margin-top: 12px;
        background: #f9f9f9;
        border-radius: 6px;
        font-size: 13px;
        color: #666;
        line-height: 1.8;
    ">
        <span>🔒 安全加密付款</span>
        <span style="margin: 0 6px;">｜</span>
        <span>🧊 全程冷凍配送</span>
        <span style="margin: 0 6px;">｜</span>
        <span>🌱 十年有機堅持</span>
    </div>
    <?php
}


/**
 * ═══════════════════════════════════════════
 * 5. 結帳頁 noindex
 * ═══════════════════════════════════════════
 */
add_action( 'wp_head', 'bcongee_checkout_noindex', 1 );

function bcongee_checkout_noindex() {
    if ( function_exists( 'is_checkout' ) && is_checkout() ) {
        echo '<meta name="robots" content="noindex, nofollow">' . "\n";
    }
}
