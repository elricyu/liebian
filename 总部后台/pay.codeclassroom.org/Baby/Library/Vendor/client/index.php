<?php
require __DIR__ . '/vendor/autoload.php';
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
class NLSFileTrans {
    // 请求参数key
    const KEY_APP_KEY = "app_key";
    const KEY_FILE_LINK = "file_link";
    const KEY_VERSION = "version";
    const KEY_ENABLE_WORDS = "enable_words";
    // 响应参数key
    const KEY_TASK_ID = "TaskId";
    const KEY_STATUS_TEXT = "StatusText";
    const KEY_RESULT = "Result";
    // 状态值
    const STATUS_SUCCESS = "SUCCESS";
    const STATUS_RUNNING = "RUNNING";
    const STATUS_QUEUEING = "QUEUEING";
    function submitFileTransRequest($appKey, $fileLink) {
        // 获取task json字符串，包含app_key和file_link参数等；
        // 新接入请使用4.0版本，已接入(默认2.0)如需维持现状，请注释掉该参数设置
        // 设置是否输出词信息，默认为false，开启时需要设置version为4.0
        $taskArr = array(self::KEY_APP_KEY => $appKey, self::KEY_FILE_LINK => $fileLink, self::KEY_VERSION => "4.0", self::KEY_ENABLE_WORDS => FALSE);
        $task = json_encode($taskArr);
        // print $task . "\n";
        try {
            // 提交请求，返回服务端的响应
            $submitTaskResponse = AlibabaCloud::nlsFiletrans()
                                              ->v20180817()
                                              ->submitTask()
                                              ->withTask($task)
                                              ->request();
            // echo 111;die;
            // print $submitTaskResponse . "\n";
            // 获取录音文件识别请求任务的ID，以供识别结果查询使用
            $taskId = NULL;
            $statusText = $submitTaskResponse[self::KEY_STATUS_TEXT];
            if (strcmp(self::STATUS_SUCCESS, $statusText) == 0) {
                $taskId = $submitTaskResponse[self::KEY_TASK_ID];
            }
            return $taskId;
        } catch (ClientException $exception) {
            // 获取错误消息
            print_r($exception->getErrorMessage());
        } catch (ServerException $exception) {
            // 获取错误消息
            print_r($exception->getErrorMessage());
        }
    }
    function getFileTransResult($taskId) {
        $result = NULL;
        while (TRUE) {
            try {
                $getResultResponse = AlibabaCloud::nlsFiletrans()
                                                 ->v20180817()
                                                 ->getTaskResult()
                                                 ->withTaskId($taskId)
                                                 ->request();
                // print "识别查询结果: " . $getResultResponse . "\n";
                $statusText = $getResultResponse[self::KEY_STATUS_TEXT];
                if (strcmp(self::STATUS_RUNNING, $statusText) == 0 || strcmp(self::STATUS_QUEUEING, $statusText) == 0) {
                    // 继续轮询
                    sleep(3);
                }
                else {
                    if (strcmp(self::STATUS_SUCCESS, $statusText) == 0) {
                        $result = $getResultResponse;
                    }
                    // 退出轮询
                    break;
                }
            } catch (ClientException $exception) {
                // 获取错误消息
                print_r($exception->getErrorMessage());
            } catch (ServerException $exception) {
                // 获取错误消息
                print_r($exception->getErrorMessage());
            }
        }
        return $result;
    }
}
$accessKeyId = "您的AccessKey Id";
$accessKeySecret = "您的AccessKey Secret";
$appKey = "您的appkey";
$fileLink = "https://aliyun-nls.oss-cn-hangzhou.aliyuncs.com/asr/fileASR/examples/nls-sample-16k.wav";
/**
  * 第一步：设置一个全局客户端
  * 使用阿里云RAM账号的AccessKey ID和AccessKey Secret进行鉴权
 */
AlibabaCloud::accessKeyClient($accessKeyId, $accessKeySecret)
            ->regionId("cn-shanghai")
            ->asGlobalClient();
$fileTrans = new NLSFileTrans();
/**
  *  第二步：提交录音文件识别请求，获取任务ID，用于后续的识别结果轮询
 */
$taskId = $fileTrans->submitFileTransRequest($appKey, $fileLink);
if ($taskId != NULL) {
    print "录音文件识别请求成功，task_id: " . $taskId . "\n";
}else {
    print "录音文件识别请求失败!";
    return ;
}
/**
  * 第三步：根据任务ID轮询识别结果
 */
$result = $fileTrans->getFileTransResult($taskId);
if ($result != NULL) {
    print "录音文件识别结果查询成功: " . $result . "\n";
}else {
    print "录音文件识别结果查询失败!";
}