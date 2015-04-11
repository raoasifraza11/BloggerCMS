<?php

/**
 * BloggerCMS - Easiest Static Blog Generator
 *
 * @author      Sarfraz Ahmed <sarfraznawaz2005@gmail.com>
 * @copyright   2015 Sarfraz Ahmed
 * @link        https://bloggercms.github.io
 * @version     1.0.0
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
class Setting implements Crud
{
    private $metaFile = 'data/settings.json';
    private $followFile = 'data/follow.json';
    private $customValuesFile = 'data/customvalues.json';

    public function get()
    {
        global $app;
        $data = MetaDataWriter::getFileData($this->metaFile);
        $data['customValues'] = MetaDataWriter::getFileData($this->customValuesFile);
        $data['follow'] = MetaDataWriter::getFileData($this->followFile);

        $app->render('settings.php', array('title' => 'Settings', 'data' => $data));
    }

    public function update()
    {
        global $app;
        $post = $app->request()->post();

        $post['only_titles'] = isset($post['only_titles']) ? 'true' : '';
        $post['url'] = rtrim(trim($post['url']), '/');

        MetaDataWriter::updateFileData($this->metaFile, $post);

        $app->flash('info', 'Saved Successfully');
        $app->redirect($_SERVER['HTTP_REFERER']);
    }

    public function updateFollow()
    {
        global $app;
        $post = $app->request()->post();

        MetaDataWriter::updateFileData($this->followFile, $post);

        $app->flash('info', 'Saved Successfully');
        $app->redirect($_SERVER['HTTP_REFERER']);
    }

    public function addCustomValue()
    {
        global $app;
        $post = $app->request()->post();


        $post['id'] = time();

        MetaDataWriter::updateFileData($this->customValuesFile, $post, true);

        $app->flash('info', 'Saved Successfully');
        $app->redirect($_SERVER['HTTP_REFERER']);
    }

    public function remove()
    {
        global $app;

        $params = $app->router()->getCurrentRoute()->getParams();
        $id = $params['param'];

        $data = MetaDataWriter::getFileData($this->customValuesFile);
        unset($data[$id]);

        MetaDataWriter::writeData($this->customValuesFile, $data);

        $app->flash('info', 'Deleted Successfully');
        $app->redirect($_SERVER['HTTP_REFERER']);
    }

    public function getBlogURL()
    {
        $data = MetaDataWriter::getFileData($this->metaFile);
        return $data['url'];
    }

    public function add()
    {
    }

    public function edit()
    {
    }
}