
/**
 * 優化「我的帳號」登入頁面
 * 手機版優化 - 字體大、行距小、資訊集中
 * 版本 1.2 - 針對老人家手機體驗優化
 */

add_action( 'wp_footer', 'bcongee_my_account_line_login_optimization' );

function bcongee_my_account_line_login_optimization() {
    
    // 只在我的帳號頁面執行
    if ( ! is_account_page() || is_user_logged_in() ) {
        return;
    }
    
    ?>
    <script>
    jQuery(document).ready(function($) {
        
        setTimeout(function() {
            
            // 找到 LINE 登入按鈕
            var lineButton = $('a[href*="line"], button:contains("LINE")').filter(function() {
                return $(this).is(':visible');
            }).first();
            
            if (lineButton.length > 0) {
                
                // 建立推薦區塊（精簡版）
                var promoBox = `
                    <div class="bcongee-line-login-promo">
                        <h3>💡 推薦使用 LINE 登入</h3>
                        
                        <div class="bcongee-line-button-wrapper">
                            <!-- LINE 按鈕會被移到這裡 -->
                        </div>
                        
                        <div class="bcongee-benefits">
                            <div class="benefit-item">✅ 免輸入帳號密碼</div>
                            <div class="benefit-item">✅ 出貨即時通知</div>
                            <div class="benefit-item">✅ 下次購買更方便</div>
                        </div>
                    </div>
                    
                    <div class="bcongee-login-divider">或使用傳統方式登入</div>
                `;
                
                // 在 LINE 按鈕前面插入推薦區塊
                lineButton.before(promoBox);
                
                // 把 LINE 按鈕移到推薦區塊內
                lineButton.appendTo('.bcongee-line-button-wrapper');
                
                // 找到傳統登入表單並加上樣式
                var loginForm = $('.woocommerce-form-login');
                if (!loginForm.hasClass('bcongee-styled')) {
                    loginForm.addClass('bcongee-styled');
                    loginForm.find('> *').wrapAll('<div class="bcongee-traditional-login"></div>');
                }
            }
            
        }, 500);
        
    });
    </script>
    
    <style>
    /* LINE 登入推薦區塊 */
    .bcongee-line-login-promo {
        background: linear-gradient(135deg, #E8F5E9 0%, #C8E6C9 100%);
        padding: 18px 16px; /* 減少內距 */
        border-radius: 10px;
        margin-bottom: 0;
        text-align: center;
        border-left: 5px solid #27AE60;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .bcongee-line-login-promo h3 {
        color: #1f2d3d;
        font-size: 17px; /* 字體稍大 */
        font-weight: bold;
        margin: 0 0 10px 0; /* 減少下方間距 */
    }
    
    /* LINE 按鈕容器 */
    .bcongee-line-button-wrapper {
        margin: 10px 0; /* 減少上下間距 */
    }
    
    /* 好處列表 - 緊湊但字體大 */
    .bcongee-benefits {
        display: flex;
        flex-direction: column;
        gap: 5px; /* 減少間距 */
        margin-top: 12px; /* 減少上方間距 */
        padding-top: 10px; /* 減少內距 */
        border-top: 1px dashed rgba(39, 174, 96, 0.4);
    }
    
    .bcongee-benefits .benefit-item {
        font-size: 15px; /* 字體加大 */
        color: #333; /* 顏色加深，更清楚 */
        font-weight: 500; /* 稍微加粗 */
        text-align: left;
        padding-left: 20px;
        line-height: 1.4; /* 行距縮小 */
    }
    
    /* 分隔線 */
    .bcongee-login-divider {
        text-align: center;
        margin: 18px 0; /* 減少間距 */
        position: relative;
        color: #999;
        font-size: 14px;
    }
    
    .bcongee-login-divider:before,
    .bcongee-login-divider:after {
        content: "";
        position: absolute;
        top: 50%;
        width: 35%;
        height: 1px;
        background: #ddd;
    }
    
    .bcongee-login-divider:before {
        left: 0;
    }
    
    .bcongee-login-divider:after {
        right: 0;
    }
    
    /* 傳統登入表單淡化 */
    .bcongee-traditional-login {
        background: #F8F9FA;
        padding: 18px 16px; /* 減少內距 */
        border-radius: 8px;
    }
    
    /* 手機版專屬優化 - 重點！ */
    @media (max-width: 768px) {
        /* 綠色框更緊湊 */
        .bcongee-line-login-promo {
            padding: 14px 14px !important; /* 進一步減少 */
        }
        
        /* 標題字體大 */
        .bcongee-line-login-promo h3 {
            font-size: 18px !important; /* 手機版字體更大 */
            margin-bottom: 8px !important;
        }
        
        /* LINE 按鈕間距小 */
        .bcongee-line-button-wrapper {
            margin: 8px 0 !important;
        }
        
        /* 好處列表超緊湊 */
        .bcongee-benefits {
            gap: 4px !important; /* 間距很小 */
            margin-top: 10px !important;
            padding-top: 8px !important;
        }
        
        /* 好處項目 - 字大、行距小 */
        .bcongee-benefits .benefit-item {
            font-size: 16px !important; /* 手機版字體更大 */
            font-weight: 600 !important; /* 更粗 */
            color: #1f2d3d !important; /* 更深色 */
            padding-left: 15px !important;
            line-height: 1.3 !important; /* 行距超小 */
        }
        
        /* 分隔線間距小 */
        .bcongee-login-divider {
            margin: 12px 0 !important;
            font-size: 13px !important;
        }
        
        /* 傳統登入區塊緊湊 */
        .bcongee-traditional-login {
            padding: 14px 12px !important;
        }
    }
    </style>
    <?php
}
