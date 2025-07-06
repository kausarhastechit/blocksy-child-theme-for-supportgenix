<?php
/**
 * Template Name: Support Genix Price page
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Blocksy
 */

get_header();
?>
<style>
.sgenix-pricing-switcher{position:relative;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-flex-wrap:wrap;-ms-flex-wrap:wrap;flex-wrap:wrap;-webkit-box-pack:center;-webkit-justify-content:center;-ms-flex-pack:center;justify-content:center;margin-bottom:40px}.sgenix-pricing-switcher input[type=radio]{position:absolute;visibility:hidden;width:0;height:0;opacity:0}.sgenix-pricing-switcher input[type=radio]:checked+label .text{opacity:1;color:#0bbc5c}.sgenix-pricing-switcher input[type=radio]:checked+label[for=yearly]:before{right:auto;left:0}.sgenix-pricing-switcher input[type=radio]:checked+label[for=lifetime]:before{right:0;left:auto}.sgenix-pricing-switcher label{font-size:16px;font-weight:600;line-height:1.625;position:relative;z-index:1;-webkit-align-self:center;-ms-flex-item-align:center;align-self:center;padding-top:2px;cursor:pointer;-webkit-transition:all .25s cubic-bezier(.645, .045, .355, 1);-o-transition:all .25s cubic-bezier(.645, .045, .355, 1);transition:all .25s cubic-bezier(.645, .045, .355, 1);color:#222}.sgenix-pricing-switcher label:before{position:absolute;top:0;width:56px;height:100%;content:"";border-radius:100px;background-color:transparent}.sgenix-pricing-switcher label[for=yearly]:before{right:auto;left:calc(100% + 18px)}.sgenix-pricing-switcher label[for=lifetime]:before{right:calc(100% + 18px);left:auto}.sgenix-pricing-switcher label .text{cursor:pointer;color:#6f7b99}.sgenix-pricing-switcher label .info{font-size:16px;font-weight:500;position:absolute;bottom:calc(100% + 16px);left:50%;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-align:center;-webkit-align-items:center;-ms-flex-align:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;-ms-flex-pack:center;justify-content:center;height:42px;padding:0 17px;padding-top:2px;content:attr(data-text);cursor:default;-webkit-transform:translateX(-50%);-ms-transform:translateX(-50%);transform:translateX(-50%);white-space:nowrap;color:#222;border-radius:4px;background-color:#0bbc5c}.sgenix-pricing-switcher label .info:before{position:absolute;top:100%;left:50%;content:"";-webkit-transform:translateX(-50%);-ms-transform:translateX(-50%);transform:translateX(-50%);border-width:8px 8px 0;border-style:solid;border-color:transparent;border-top-color:#f88}.sgenix-pricing-switcher .sgenix-switch{position:relative;width:56px;height:28px;margin:0 18px;border-radius:100px;background:#0bbc5c}.sgenix-pricing-switcher .sgenix-switch:before{position:absolute;top:3px;left:3px;width:22px;height:22px;content:"";-webkit-transition:all .25s cubic-bezier(.645, .045, .355, 1);-o-transition:all .25s cubic-bezier(.645, .045, .355, 1);transition:all .25s cubic-bezier(.645, .045, .355, 1);border-radius:50%;background-color:#fff}.sgenix-pricing-switcher .sgenix-switch.lifetime:before{-webkit-transform:translateX(calc(100% + 6px));-ms-transform:translateX(calc(100% + 6px));transform:translateX(calc(100% + 6px))}@media only screen and (max-width:767px),only screen and (min-width:768px) and (max-width:991px){.sgenix-pricing-switcher{margin-top:60px}.sgenix-pricing-switcher label{margin-bottom:0px;color:#333}}.sgenix-pricing-wrapper{position:relative;margin-bottom:0;padding-left:0;list-style:none}.sgenix-pricing-wrapper.is-switched .is-visible{-webkit-transform:rotateY(180deg);transform:rotateY(180deg);-webkit-animation:sgenix-rotate .5s;animation:sgenix-rotate .5s}.sgenix-pricing-wrapper.is-switched .is-hidden{-webkit-transform:rotateY(0);transform:rotateY(0);-webkit-animation:sgenix-rotate-inverse .5s;animation:sgenix-rotate-inverse .5s;opacity:0}.sgenix-pricing-wrapper.is-switched .is-selected{opacity:1}.sgenix-pricing-wrapper.is-switched.reverse-animation .is-visible{-webkit-transform:rotateY(-180deg);transform:rotateY(-180deg);-webkit-animation:sgenix-rotate-back .5s;animation:sgenix-rotate-back .5s}.sgenix-pricing-wrapper.is-switched.reverse-animation .is-hidden{-webkit-transform:rotateY(0);transform:rotateY(0);-webkit-animation:sgenix-rotate-inverse-back .5s;animation:sgenix-rotate-inverse-back .5s;opacity:0}.sgenix-pricing-wrapper.is-switched.reverse-animation .is-selected{opacity:1}.sgenix-pricing-wrapper .is-visible{position:relative;z-index:5}.sgenix-pricing-wrapper .is-hidden{position:absolute;z-index:1;top:0;left:0;width:100%;height:100%;-webkit-transform:rotateY(180deg);transform:rotateY(180deg)}.sgenix-pricing-wrapper .is-selected{z-index:3!important}.sgenix-pricing-wrapper.recommended>li .sgenix-pricing-footer .sgenix-btn .inner{background:#0bbc5c;color:#fff}.sgenix-pricing-wrapper.recommended>li .sgenix-pricing-footer .sgenix-btn .inner:hover{-webkit-transform:translateY(-3px);-ms-transform:translateY(-3px);transform:translateY(-3px)}.sgenix-pricing-wrapper.recommended>li{border:1px solid #d1d1d1}.plan__star{position:absolute;right:10px;background:-webkit-linear-gradient(45deg,#fd5900,#e3c70d);background:-o-linear-gradient(45deg,#fd5900,#e3c70d);background:linear-gradient(45deg,#fd5900,#e3c70d);padding:10px;border-radius:50%;top:10px}.sgenix-pricing-header .price{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:inline-flex;margin-bottom:15px;padding:15px 0;border-bottom:1px solid rgba(9,9,9,.04)}.sgenix-pricing-header .price span{line-height:1;color:#5a5a5a}.sgenix-pricing-header .price span.old-price{font-size: 16px;text-decoration: line-through;margin-right: 10px;}.sgenix-pricing-header .price span.value{font-size:28px;font-weight:600;margin-right:5px}.sgenix-pricing-header .price span.duration{font-size:16px;font-weight:500;-webkit-align-self:flex-end;-ms-flex-item-align:end;align-self:flex-end;color:#999}.sgenix-pricing-header .website span{font-size:16px;font-weight:500;line-height:1;color:#222}.sgenix-pricing-header .website .number{margin-right:5px}.is-switched .sgenix-pricing-body{overflow:hidden}.sgenix-pricing-features{margin-bottom:15px;padding-left:0;list-style:none}.sgenix-pricing-features li+li{margin-top:15px}.sgenix-pricing-footer a.sgenix-btn{background:#00c853;border-radius:4px;color:#fff;font-size:14px;font-weight:600;padding:12px 25px;text-decoration:none;text-transform:uppercase;transition:.3s}.sgenix-pricing-footer a.sgenix-btn:hover{border-color: #ff6e30;background:#ff6e30}@-webkit-keyframes sgenix-rotate{0%{-webkit-transform:perspective(2000px) rotateY(0);transform:perspective(2000px) rotateY(0)}70%{-webkit-transform:perspective(2000px) rotateY(200deg);transform:perspective(2000px) rotateY(200deg)}to{-webkit-transform:perspective(2000px) rotateY(180deg);transform:perspective(2000px) rotateY(180deg)}}@keyframes sgenix-rotate{0%{-webkit-transform:perspective(2000px) rotateY(0);transform:perspective(2000px) rotateY(0)}70%{-webkit-transform:perspective(2000px) rotateY(200deg);transform:perspective(2000px) rotateY(200deg)}to{-webkit-transform:perspective(2000px) rotateY(180deg);transform:perspective(2000px) rotateY(180deg)}}@-webkit-keyframes sgenix-rotate-inverse{0%{-webkit-transform:perspective(2000px) rotateY(-180deg);transform:perspective(2000px) rotateY(-180deg)}70%{-webkit-transform:perspective(2000px) rotateY(20deg);transform:perspective(2000px) rotateY(20deg)}to{-webkit-transform:perspective(2000px) rotateY(0);transform:perspective(2000px) rotateY(0)}}@keyframes sgenix-rotate-inverse{0%{-webkit-transform:perspective(2000px) rotateY(-180deg);transform:perspective(2000px) rotateY(-180deg)}70%{-webkit-transform:perspective(2000px) rotateY(20deg);transform:perspective(2000px) rotateY(20deg)}to{-webkit-transform:perspective(2000px) rotateY(0);transform:perspective(2000px) rotateY(0)}}@-webkit-keyframes sgenix-rotate-back{0%{-webkit-transform:perspective(2000px) rotateY(0);transform:perspective(2000px) rotateY(0)}70%{-webkit-transform:perspective(2000px) rotateY(-200deg);transform:perspective(2000px) rotateY(-200deg)}to{-webkit-transform:perspective(2000px) rotateY(-180deg);transform:perspective(2000px) rotateY(-180deg)}}@keyframes sgenix-rotate-back{0%{-webkit-transform:perspective(2000px) rotateY(0);transform:perspective(2000px) rotateY(0)}70%{-webkit-transform:perspective(2000px) rotateY(-200deg);transform:perspective(2000px) rotateY(-200deg)}to{-webkit-transform:perspective(2000px) rotateY(-180deg);transform:perspective(2000px) rotateY(-180deg)}}@-webkit-keyframes sgenix-rotate-inverse-back{0%{-webkit-transform:perspective(2000px) rotateY(180deg);transform:perspective(2000px) rotateY(180deg)}70%{-webkit-transform:perspective(2000px) rotateY(-20deg);transform:perspective(2000px) rotateY(-20deg)}to{-webkit-transform:perspective(2000px) rotateY(0);transform:perspective(2000px) rotateY(0)}}@keyframes sgenix-rotate-inverse-back{0%{-webkit-transform:perspective(2000px) rotateY(180deg);transform:perspective(2000px) rotateY(180deg)}70%{-webkit-transform:perspective(2000px) rotateY(-20deg);transform:perspective(2000px) rotateY(-20deg)}to{-webkit-transform:perspective(2000px) rotateY(0);transform:perspective(2000px) rotateY(0)}}.border-btn .inner{border-bottom:1px solid #fff;padding:1px;font-weight:600;color:#fff}.border-btn .inner.text-white{border-bottom:1px solid #fff}.section-space--pt_120{padding-top:120px}.sgenix-pricing-list{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-flex-wrap:wrap;-ms-flex-wrap:wrap;flex-wrap:wrap;margin-top:0;margin-right:-15px;margin-bottom:-40px;margin-left:-15px;padding-left:0;list-style:none}.sgenix-pricing-wrapper{position:relative;margin-bottom:0;padding-left:0;list-style:none}.sgenix-pricing-wrapper .is-hidden{position:absolute;z-index:1;top:0;left:0;width:100%;height:100%;-webkit-transform:rotateY(180deg);transform:rotateY(180deg)}.sgenix-pricing-wrapper .is-visible{position:relative;z-index:5}
</style>
<div class="site-wrapper-reveal">
    <!-- Page Header Start --->
    <div class="pricing-page-header sgenix-sgenix-hero-bg-theme">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="pricing-header-content">
                        <h1 class="sgenix-hero-title"><?php the_field('support_genix_page_title'); ?></h1>
                        <p class="sgenix-dec mt-3"><?php the_field('support_genix_page_header_description'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End --->
		<div class="find-plan-area section-space--pt_120 section-space--pb_100" id="sg-find-plan-area">

<div class="container">
  <div class="row">
      <div class="col-lg-12">
        <div class="sgenix-pricing-container">
        <?php
            $pricing_plan_switcher = get_field('pricing_plan_switcher');
            if( $pricing_plan_switcher ): ?>
            <div class="sgenix-pricing-switcher">
                <input type="radio" name="duration" value="yearly" id="yearly">
                <label for="yearly"><span class="text"><?php echo esc_html( $pricing_plan_switcher['pricing_switcher_yearly_text']); ?></span></label>
                <span class="sgenix-switch lifetime"></span>
                <input type="radio" name="duration" value="lifetime" id="lifetime" checked>
                <label for="lifetime"><span class="text"><?php echo esc_html( $pricing_plan_switcher['pricing_switcher_lifetime_text']); ?></span></label>
            </div>
            <?php endif; ?>
            <ul class="sgenix-pricing-list sgenix-bounce-invert">

              <?php 
              $sgenix_yearly_pricing_list_formatted = array();

              // Check rows exists.
              if( have_rows('sgenix_yearly_pricing_list') ):
                  $count = 0;

                  while( have_rows('sgenix_yearly_pricing_list') ) : the_row();
                      $pricing_list_item_data = '<li data-type="yearly" class="is-visible">';
                      $pricing_list_item_data .= '<header class="sgenix-pricing-header">
                          <div class="website">
                              <span class="price-dec-title">'.esc_html( get_sub_field('sgenix_yearly_pricing_top_header') ).'</span>
                          </div>
                          <div class="price-title">
                          <h2 class="title">'.esc_html( get_sub_field('sgenix_yearly_pricing_header_title') ).'</h2>
                          </div>
                          
                          <div class="price">
                            <span class="old-price"> '.esc_html( get_sub_field('support_genix_yearlly_old_price') ).'</span>
                              <span class="value"> '.esc_html( get_sub_field('sgenix_yearly_pricing_new_price') ).'</span>
                              <span class="duration">'.esc_html( get_sub_field('sgenix_yearly_pricing_duration') ).'</span>
                          </div>
						  <div class="sgenix-badge-text">'.esc_html( get_sub_field('sgenix_discount_yearly_badge_text') ).'</div>';
						  
                          if(get_sub_field('yearly_offer_price_notifications')) : 
                          $pricing_list_item_data .='<div class="offer-notification-text">'.esc_html( get_sub_field('yearly_offer_price_notifications') ).'</div>';
                          endif;
                          $pricing_list_item_data .='</header>

                      <div class="sgenix-pricing-body">
                          <ul class="sgenix-pricing-features">';

                          if( have_rows('sgenix_yearly_pricing_features') ):
                              while( have_rows('sgenix_yearly_pricing_features') ) : the_row();
                                  $pricing_list_item_data .= '<li>'.'<span>'.esc_html( get_sub_field('sgenix_yearly_pricing_features_title') ).'</span>'.'</li>';
                              endwhile;
                          endif;
                              
                          $pricing_list_item_data .= '</ul>
                      </div>

                      <footer class="sgenix-pricing-footer text-center">
                          <a class="sgenix-btn paddle_button" href="#!" data-product='.get_sub_field('pricing_yearly_footer_button_data_attribute').' data-init="true"><span class="inner">'.esc_html( get_sub_field('pricing_yearly_footer_button_text') ).'</span></a>
                      </footer>
                  </li>';

              $sgenix_yearly_pricing_list_formatted[ $count ]['yearly'] = $pricing_list_item_data;

              $count++;
              endwhile;
              else :
              endif;


              // Check rows exists.
              if( have_rows('sgenix_lifetime_pricing_list') ):
                  $count = 0;

                  while( have_rows('sgenix_lifetime_pricing_list') ) : the_row();
                      $pricing_list_item_data = '<li data-type="lifetime" class="is-hidden">';

                      $pricing_list_item_data .= '<header class="sgenix-pricing-header">
                          <div class="website">
                              <span class="price-dec-title">'.esc_html( get_sub_field('sgenix_lifetime_pricing_top_header') ).'</span>
                          </div>
                          <div class="price-title">
                            <h2 class="title">'.esc_html( get_sub_field('sgenix_lifetime_pricing_header_title') ).'</h2>
                          </div>
                          
                          <div class="price">
                              <span class="old-price"> '.esc_html( get_sub_field('support_genix_lifetime_old_price') ).'</span>
							  <span class="value"> '.esc_html( get_sub_field('sgenix_lifetime_pricing_new_price') ).'</span>
                              <span class="duration">'.esc_html( get_sub_field('sgenix_lifetime_pricing_duration') ).'</span>
                          </div>
						  <div class="sgenix-badge-text">'.esc_html( get_sub_field('sgenix_discount_lifetime_badge_text') ).'</div>';
						  
						if(get_sub_field('offer_price_notifications')) : 
						$pricing_list_item_data .='<div class="offer-notification-text">'.esc_html( get_sub_field('offer_price_notifications') ).'</div>';
						endif;
                      $pricing_list_item_data .='</header>

                      <div class="sgenix-pricing-body">
                          <ul class="sgenix-pricing-features">';

                          if( have_rows('sgenix_lifetime_pricing_features') ):
                              while( have_rows('sgenix_lifetime_pricing_features') ) : the_row();
                                  $pricing_list_item_data .= '<li>'.'<span>'.esc_html( get_sub_field('sgenix_lifetime_pricing_features_title') ).'</span>'.'</li>';
                              endwhile;
                          endif;
                              
                          $pricing_list_item_data .= '</ul>
                      </div>

                      <footer class="sgenix-pricing-footer text-center">
                          <a class="sgenix-btn paddle_button" href="#!" data-product='.esc_html(get_sub_field('pricing_lifetime_footer_button_data_attribute')).' data-init="true"><span class="inner">'.esc_html( get_sub_field('pricing_lifetime_footer_button_text') ).'</span></a>
                      </footer>
                  </li>';

                  $sgenix_yearly_pricing_list_formatted[ $count ]['lifetime'] = $pricing_list_item_data;
              
              $count++;
              endwhile;
          else :
          endif;

          if ( is_array( $sgenix_yearly_pricing_list_formatted ) && ! empty( $sgenix_yearly_pricing_list_formatted ) ) {
              $count = 0;

              foreach ( $sgenix_yearly_pricing_list_formatted as $list_group ) {
                  if ( 2 === $count ) {echo '<li><ul class="sgenix-pricing-wrapper box-primary text-center">';
                  } else {
                      echo '<li><ul class="sgenix-pricing-wrapper text-center">';
                  }

                  if ( is_array( $list_group ) && ! empty( $list_group ) ) {
                      foreach ( $list_group as $list_item ) {
                          echo $list_item;
                      }
                  }

                  echo '</ul></li>';

                  $count++;
              }
          }
              ?>


            </ul>	
        </div>

        </div>
      </div>
    </div>

</div>		
</div>

<?php get_footer(); ?>