<?php
/**
 * 图片上传处理
 * Date: 2018/10/8
 * Time: 14:58
 */
namespace Frameworks\Component\Upload\Processor;

use Frameworks\Services\Basic\Processor\BaseWorkProcessor;

class UploadProcessor extends BaseWorkProcessor
{
    protected $request = null;
    protected $name = null;

    public function _init($request, $name = 'file')
    {
        $this->request = $request;
        $this->name = $name;
        $this->status = 0;
        return $this;
    }

    public function process()
    {
        try {
            $file = $this->request->file($this->name);
            $originalName = $file->getClientOriginalName();
            $targetFileName = $file->hashName();
            $path = $file->store(date('Ymd'), 'public');
            if (!empty($path)) {
                $this->status = 1;
                return $this->parseResult('', $path, $originalName, $targetFileName);
            } else {
                return $this->parseResult('上传异常', '', '', '');
            }
        } catch (\Exception $e) {
            return $this->parseResult('上传异常', '', '', '');
        }
    }
}
