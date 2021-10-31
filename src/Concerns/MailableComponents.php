<?php

namespace Eduka\Concerns;

trait MailableComponents
{
    public function headInit($mjml, $preview)
    {
        $head = $mjml->with('mj-head');

        $head->with('mj-preview', $preview);
        $head->with('mj-style', '.text-link { color: #FFFFFF } .decoration-none { text-decoration: none }', ['inline' => 'inline']);

        $head->with('mj-attributes')
                ->with('mj-all', ['font-family' => 'Open Sans',
                    'font-size' => '14px',
                    'letter-spacing' => '0.02rem',
                    'background-color' => '#EBF4FF', /* Indigo 100 */
                    'line-height' => '1.5rem',
                    'padding' => '0px', ]);

        return $head;
    }

    public function bodyCenteredHeader($mjml, $title)
    {
        $body = $mjml->with('mj-body');

        $body->backgroundColor('#010414')
             ->width('600px')
             ->with('mj-section')
                ->with('mj-column')
                    ->with('mj-spacer', ['container-background-color' => '#010414'])
                        ->height('50px');

        $body->with('mj-section')
                ->with('mj-column')
                    ->backgroundColor('#010414')
                    ->padding('20px')
                    ->verticalAlign('middle')
                        ->with('mj-image')
                            ->src(url('/').'images/logo-navbar-white.png')
                            ->align('center')
                            ->width('70px');

        $body->with('mj-section')
                ->with('mj-column')
                    ->padding('30px')
                    ->paddingTop('30px')
                        ->with('mj-text', $title)
                            ->align('center')
                            ->fontWeight('600')
                            ->fontSize('25px');

        return $body;
    }

    public function footerCenteredLink($body)
    {
        $body->with('mj-section')
                ->with('mj-column')
                    ->backgroundColor('#3C366B')
                    ->padding('5px')
                        ->with('mj-text', '<a href="https://www.masteringnova.com" target="_blank" class="text-link decoration-none">www.masteringnova.com</a>')
                            ->align('center')
                            ->fontSize('11px')
                            ->color('#FFFFFF');

        $body->width('600px')
             ->with('mj-section')
                ->with('mj-column')
                    ->with('mj-spacer', ['container-background-color' => '#010414'])
                        ->height('50px');

        return $body;
    }
}
