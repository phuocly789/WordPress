<?php
/**
 * Displays the menus and widgets at the end of the main element.
 * Visually, this output is presented as part of the footer element.
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

$has_sidebar_1 = is_active_sidebar('sidebar-1');
$has_sidebar_2 = is_active_sidebar('sidebar-2');
$has_sidebar_3 = is_active_sidebar('sidebar-3');
$has_sidebar_4 = is_active_sidebar('sidebar-4');
$has_sidebar_5 = is_active_sidebar('sidebar-5');

// Only output the container if there are elements to display.
if ($has_sidebar_1 || $has_sidebar_2 || $has_sidebar_3 || $has_sidebar_4 || $has_sidebar_5) {
    ?>
    <!-- Footer -->
    <section id="footer">
        <div class="container">
            <div class="row text-center text-xs-center text-sm-left text-md-left">
                <div class="col-xs-12 col-sm-4 col-md-4">
                    <?php if ($has_sidebar_1) : ?>
                        <?php dynamic_sidebar('sidebar-1'); ?>
                    <?php endif; ?>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-4">
                    <?php if ($has_sidebar_2) : ?>
                        <?php dynamic_sidebar('sidebar-2'); ?>
                    <?php endif; ?>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-4 ">
                    <?php if ($has_sidebar_3) : ?>
                        <?php dynamic_sidebar('sidebar-3'); ?>
                    <?php endif; ?>
                </div>
            </div>
            <!-- <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 mt-2 mt-sm-5">
                    <?php //if ($has_sidebar_4) : ?>
                        <?php //dynamic_sidebar('sidebar-4'); ?>
                    <?php //endif; ?>
                </div>
                <hr>
            </div> -->
            <!-- <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 mt-2 mt-sm-2 text-center text-white">
                    <?php //if //($has_sidebar_5) : ?>
                        <?php //dynamic_sidebar('sidebar-5'); ?>
                    <?php // endif; ?>
                </div>
                <hr>
            </div> -->
        </div>
    </section>
    <!-- ./Footer -->
    <?php
}